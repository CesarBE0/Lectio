<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $code = strtoupper($request->code);
        $coupon = \App\Models\Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'El cupón no existe.']);
        }

        if ($coupon->user_id !== null) {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Debes iniciar sesión para usar este cupón.']);
            }

            if ($coupon->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Este cupón pertenece a otro usuario y es intransferible.']);
            }
        }

        if ($code === 'BIENVENIDA10') {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Debes iniciar sesión para usar este cupón.']);
            }

            if (Auth::user()->welcome_coupon_used) {
                return response()->json(['success' => false, 'message' => 'Ya has canjeado tu descuento de bienvenida anteriormente.']);
            }
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $coupon->discount_percentage
        ]);

        $cartItems = session()->get('cart', []);
        $subtotal = 0;
        foreach($cartItems as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $discountAmount = $subtotal * ($coupon->discount_percentage / 100);
        $subtotalConDescuento = $subtotal - $discountAmount;
        $shipping = $subtotalConDescuento >= 30 ? 0 : 4.99;
        $total = $subtotalConDescuento + $shipping;

        return response()->json([
            'success' => true,
            'message' => '¡Cupón del ' . $coupon->discount_percentage . '% aplicado!',
            'code' => $coupon->code,
            'discountAmount' => $discountAmount,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }

    public function removeCoupon()
    {
        session()->forget('coupon');

        $cartItems = session()->get('cart', []);
        $subtotal = 0;
        foreach($cartItems as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        $shipping = $subtotal >= 30 ? 0 : 4.99;
        $total = $subtotal + $shipping;

        return response()->json([
            'success' => true,
            'message' => 'Cupón eliminado.',
            'shipping' => $shipping,
            'total' => $total
        ]);
    }

    public function index()
    {
        $cartItems = session()->get('cart', []);

        $subtotal = 0;
        foreach($cartItems as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $discountAmount = 0;
        if (session()->has('coupon')) {
            $discountAmount = $subtotal * (session('coupon')['discount'] / 100);
        }

        $subtotalConDescuento = $subtotal - $discountAmount;
        $shipping = $subtotalConDescuento >= 30 ? 0 : 4.99;
        $total = $subtotalConDescuento + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'discountAmount', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $cartItems = session()->get('cart', []);
            $subtotal = 0;
            foreach($cartItems as $item) {
                $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            }

            $discountAmount = 0;
            if (session()->has('coupon')) {
                $discountAmount = $subtotal * (session('coupon')['discount'] / 100);
            }

            $subtotalConDescuento = $subtotal - $discountAmount;
            $shipping = $subtotalConDescuento >= 30 ? 0 : 4.99;
            $total = $subtotalConDescuento + $shipping;

            // Cobro mediante Stripe
            $charge = \Stripe\Charge::create([
                'amount' => round($total * 100),
                'currency' => 'eur',
                'description' => 'Compra en Lectio - Usuario: ' . Auth::user()->email,
                'source' => $request->stripeToken,
            ]);

            $user = Auth::user();

            if (session()->has('coupon') && session('coupon')['code'] === 'BIENVENIDA10') {
                $user->update(['welcome_coupon_used' => true]);
            }

            $user->increment('points', floor($total));

            // 1. GENERAR NÚMERO DE PEDIDO ÚNICO
            do {
                $randomNumber = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
                $orderNumber = 'LCT-' . $randomNumber;
                // 🚀 CAMBIO: Ahora buscamos en 'orders' en lugar de 'library'
                $exists = \Illuminate\Support\Facades\DB::table('orders')->where('trackingNumber', $orderNumber)->exists();
            } while ($exists);

            // 2. EL DETECTOR DE FORMATOS
            $hasPhysicalBook = false;
            foreach ($cartItems as $item) {
                $formato = strtolower(trim($item['format'] ?? ''));
                if (str_contains($formato, 'tapa dura') || str_contains($formato, 'físico')) {
                    $hasPhysicalBook = true;
                    break;
                }
            }
            $initialStatus = $hasPhysicalBook ? 'preparando' : 'entregado';

            // 3. RECUPERAR ID DEL CUPÓN (Si se ha usado)
            $couponId = null;
            if (session()->has('coupon')) {
                // Usamos DB facade para evitar errores con nombres de Primary Keys
                $couponRecord = \Illuminate\Support\Facades\DB::table('coupons')
                    ->where('code', session('coupon')['code'])
                    ->first();
                if ($couponRecord) {
                    $couponId = $couponRecord->couponId ?? $couponRecord->id ?? null;
                }
            }

            // 4. CREAR LA FACTURA PRINCIPAL (Tabla orders)
            $userId = Auth::id(); // Asegurar ID del usuario
            $orderId = \Illuminate\Support\Facades\DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'totalPrice' => $total,
                'status' => $initialStatus,
                'trackingNumber' => $orderNumber,
                'created_at' => now(),
            ]);

            // 5. SEPARAR LÍNEAS DE FACTURA Y ACCESO A LA BIBLIOTECA
            foreach ($cartItems as $cartKey => $item) {
                $itemPrice = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 1;

                // Extraemos el formatId de la clave del carrito (que es "bookId-formatId")
                $parts = explode('-', $cartKey);
                $formatId = $parts[1] ?? null;

                // Prevención de fallos si la clave no tiene el formato
                if (!$formatId) {
                    $bookId = $item['book_id'] ?? $item['id'];
                    $f = \Illuminate\Support\Facades\DB::table('formats')
                        ->where('bookId', $bookId)->orWhere('book_id', $bookId)
                        ->where('type', $item['format'])
                        ->first();
                    $formatId = $f ? ($f->formatId ?? $f->id ?? 1) : 1;
                }

                // 5.1 Crear la línea de producto vendido (Tabla order_items)
                \Illuminate\Support\Facades\DB::table('order_items')->insert([
                    'orderId' => $orderId,
                    'formatId' => $formatId,
                    'quantity' => $quantity,
                    'priceAtPurchase' => $itemPrice,
                ]);

                // 5.2 Dar acceso en la biblioteca personal (Tabla user_library)
                // Evitamos duplicados si el usuario compra dos veces lo mismo
                $hasBook = \Illuminate\Support\Facades\DB::table('user_library')
                    ->where('user_Id', Auth::user()->userId ?? Auth::id())
                    ->where('formatId', $formatId)
                    ->exists();

                if (!$hasBook) {
                    \Illuminate\Support\Facades\DB::table('user_library')->insert([
                        'user_Id' => Auth::user()->userId ?? Auth::id(),
                        'formatId' => $formatId,
                        'is_favorite' => false,
                        'acquired_at' => now(),
                    ]);
                }
            }

            // Enviamos el correo con la factura
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OrderInvoice($orderNumber, $cartItems, $subtotal, $discountAmount, $shipping, $total));

            // Limpiamos sesión
            session()->forget(['cart', 'coupon']);

            return redirect()->route('library.index')->with('success', '¡Pago confirmado! Has ganado ' . floor($total) . ' Puntos Lectio.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error en el pago: ' . $e->getMessage());
        }
    }
}
