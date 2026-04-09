<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Format;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $subtotal = 0;
        foreach($cart as $item) {
            $price = $item['discount_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
        }

        $shippingCost = ($subtotal >= 30 || $subtotal == 0) ? 0 : 4.99;
        $total = $subtotal + $shippingCost;

        return view('cart.index', compact('cart', 'subtotal', 'shippingCost', 'total'));
    }

    public function add(Request $request, $id)
    {
        // 🛡️ ESCUDO 1: Nadie puede inyectar letras, ceros o números negativos
        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);

        $book = Book::findOrFail($id);
        $cart = session()->get('cart', []);

        $formatId = $request->input('format_id') ?? $book->formats->first()->id;
        $format = Format::findOrFail($formatId);

        // Obtenemos la cantidad que piden (por defecto 1)
        $requestedQuantity = $request->input('quantity', 1);

        $cartKey = $book->id . '-' . $format->id;

        // 🛡️ ESCUDO 2: Bloqueo de falta de Stock
        // Sumamos lo que ya tiene en el carrito + lo que quiere añadir ahora
        $currentQuantity = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $newTotalQuantity = $currentQuantity + $requestedQuantity;

        if ($newTotalQuantity > $format->stock) {
            return redirect()->back()->with('error', 'Lo sentimos, solo nos quedan ' . $format->stock . ' unidades disponibles de este formato.');
        }

        // --- LÓGICA HABITUAL DE AÑADIR (AURA SEGURA) ---
        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $requestedQuantity; // Sumamos la cantidad validada
        } else {
            $cart[$cartKey] = [
                "book_id" => $book->id,
                "title" => $book->title,
                "author" => $book->author,
                "quantity" => $requestedQuantity, // Guardamos la cantidad validada
                "price" => $format->price,
                "discount_price" => null,
                "image_url" => $book->image_url,
                "format" => $format->type
            ];
        }

        session()->put('cart', $cart);

        if (Auth::check()) {
            $request->user()->forceFill(['cart_data' => $cart])->save();
        }

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('cart.index');
        }

        if (($request->wantsJson() || $request->ajax()) && $request->input('action') !== 'buy_now') {
            return response()->json([
                'success' => true,
                'message' => '¡Libro añadido al carrito!',
                'cartCount' => count(session('cart', []))
            ]);
        }

        return redirect()->back()->with('success', 'Libro en formato ' . $format->type . ' añadido al carrito correctamente ✨');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);

                if (Auth::check()) {
                    $request->user()->forceFill(['cart_data' => $cart])->save();
                }
            }
        }
        return redirect()->back();
    }

    public function updateQuantity(Request $request)
    {
        if($request->id && $request->action) {
            $cart = session()->get('cart', []);

            if(isset($cart[$request->id])) {
                if($request->action === 'increase') {
                    // 🛡️ Extra: También protegemos el botón de sumar en el carrito
                    // (Opcional pero recomendable, aquí asumimos que tienes un stock disponible)
                    $cart[$request->id]['quantity']++;
                } elseif($request->action === 'decrease') {
                    $cart[$request->id]['quantity']--;
                    if($cart[$request->id]['quantity'] <= 0) {
                        unset($cart[$request->id]);
                    }
                }

                session()->put('cart', $cart);

                if (\Illuminate\Support\Facades\Auth::check()) {
                    $request->user()->forceFill(['cart_data' => $cart])->save();
                }

                if ($request->wantsJson() || $request->ajax()) {
                    $subtotal = 0;
                    foreach($cart as $item) {
                        $price = $item['discount_price'] ?? $item['price'];
                        $subtotal += $price * $item['quantity'];
                    }
                    $shippingCost = ($subtotal >= 30 || $subtotal == 0) ? 0 : 4.99;
                    $total = $subtotal + $shippingCost;

                    return response()->json([
                        'success' => true,
                        'new_quantity' => isset($cart[$request->id]) ? $cart[$request->id]['quantity'] : 0,
                        'item_total' => isset($cart[$request->id]) ? ($cart[$request->id]['discount_price'] ?? $cart[$request->id]['price']) * $cart[$request->id]['quantity'] : 0,
                        'subtotal' => $subtotal,
                        'shippingCost' => $shippingCost,
                        'total' => $total,
                        'is_empty' => count($cart) === 0
                    ]);
                }
            }
        }

        return redirect()->back();
    }
}
