<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    // --- 1. FUNCIONES PARA EL CUPÓN (AJAX) ---
    public function applyCoupon(Request $request)
    {
        $code = strtoupper($request->code);
        $coupon = \App\Models\Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'El cupón no existe.']);
        }

        // --- LÓGICA DE BIENVENIDA ---
        if ($code === 'BIENVENIDA10') {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Debes iniciar sesión para usar este cupón.']);
            }

            if (Auth::user()->welcome_coupon_used) {
                return response()->json(['success' => false, 'message' => 'Ya has canjeado tu descuento de bienvenida anteriormente.']);
            }
        }
        // ----------------------------

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $coupon->discount_percentage
        ]);

        // Recalculamos totales para enviárselos a JavaScript
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

        // Recalculamos totales sin el cupón
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

    // --- 2. ACTUALIZAMOS EL CÁLCULO EN INDEX ---
    public function index()
    {
        $cartItems = session()->get('cart', []);

        $subtotal = 0;
        foreach($cartItems as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        // Calculamos el descuento si hay un cupón en la sesión
        $discountAmount = 0;
        if (session()->has('coupon')) {
            $discountAmount = $subtotal * (session('coupon')['discount'] / 100);
        }

        $subtotalConDescuento = $subtotal - $discountAmount;

        // Envío a 4.99€, gratis a partir de 30€ (sobre el precio con descuento)
        $shipping = $subtotalConDescuento >= 30 ? 0 : 4.99;
        $total = $subtotalConDescuento + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'discountAmount', 'shipping', 'total'));
    }

    // --- 3. ACTUALIZAMOS EL CÁLCULO EN PROCESS ---
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

            $charge = \Stripe\Charge::create([
                'amount' => round($total * 100), // Stripe cobra en céntimos
                'currency' => 'eur',
                'description' => 'Compra en Lectio - Usuario: ' . Auth::user()->email,
                'source' => $request->stripeToken,
            ]);

            $user = Auth::user();

            if (session()->has('coupon') && session('coupon')['code'] === 'BIENVENIDA10') {
                $user->update(['welcome_coupon_used' => true]);
            }

            $discountPercentage = session()->has('coupon') ? session('coupon')['discount'] : 0;
            $totalBooks = count($cartItems);
            $shippingPerItem = $totalBooks > 0 ? ($shipping / $totalBooks) : 0;

            // 🌟 MAGIA AQUÍ: Generar número de pedido ÚNICO de 8 cifras
            do {
                // Genera 8 números al azar (ej: 04928174)
                $randomNumber = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
                $orderNumber = 'LCT-' . $randomNumber;

                // Busca en la base de datos si ya existe ese número
                $exists = \Illuminate\Support\Facades\DB::table('library')->where('order_number', $orderNumber)->exists();
            } while ($exists); // Si existe, el bucle se repite y genera otro distinto

            $booksToSync = [];
            foreach($cartItems as $item) {
                $id = $item['id'] ?? $item['book_id'] ?? ($item['book']['id'] ?? null);

                $itemPrice = $item['price'] ?? 0;
                $itemDiscount = $itemPrice * ($discountPercentage / 100);

                if($id) {
                    $booksToSync[$id] = [
                        'format' => $item['format'] ?? 'Físico',

                        // 👇 ¡AQUÍ ESTÁ LA LÍNEA NUEVA PARA EL TOP VENTAS! 👇
                        'quantity' => $item['quantity'] ?? 1,

                        'progress' => 0,
                        'is_favorite' => false,
                        'address' => $request->address,
                        'city' => $request->city,
                        'price' => $itemPrice,
                        'discount' => $itemDiscount,
                        'shipping' => $shippingPerItem,
                        'order_number' => $orderNumber
                    ];
                }
            }

            // El sistema guardará los libros con todos los datos anteriores, incluida la cantidad
            if(!empty($booksToSync)) {
                $user->books()->syncWithoutDetaching($booksToSync);
            }

            // El correo ahora usa el número de pedido real
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OrderInvoice($orderNumber, $cartItems, $subtotal, $discountAmount, $shipping, $total));

            // Vaciamos el carrito Y el cupón de la sesión
            session()->forget(['cart', 'coupon']);

            return redirect()->route('library.index')->with('success', '¡Pago confirmado! Tus libros te esperan en tu biblioteca.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error en el pago: ' . $e->getMessage());
        }
    }
}
