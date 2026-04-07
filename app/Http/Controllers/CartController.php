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
        // Recuperamos el carrito de la sesión
        $cart = session()->get('cart', []);

        // 1. Calculamos el subtotal (la suma de los libros)
        $subtotal = 0;
        foreach($cart as $item) {
            $price = $item['discount_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
        }

        // 2. LÓGICA DE ENVÍO: Si supera o iguala los 50€, es 0. Si no, 4.99€
        // (Añadimos la condición $subtotal > 0 para no cobrar envío en carritos vacíos por si acaso)
        $shippingCost = ($subtotal >= 30 || $subtotal == 0) ? 0 : 4.99;

        // 3. Calculamos el Total Final
        $total = $subtotal + $shippingCost;

        // Pasamos todas estas nuevas variables a la vista
        return view('cart.index', compact('cart', 'subtotal', 'shippingCost', 'total'));
    }

    public function add(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $cart = session()->get('cart', []);

        // 1. Capturamos el ID del formato enviado desde el botón Black & Gold
        $formatId = $request->input('format_id') ?? $book->formats->first()->id;
        $format = Format::findOrFail($formatId);

        // 2. Clave única para el carrito: ID_Libro - ID_Formato (ej: 14-2)
        $cartKey = $book->id . '-' . $format->id;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "book_id" => $book->id, // Guardamos el ID real para el enlace
                "title" => $book->title,
                "author" => $book->author,
                "quantity" => 1,
                "price" => $format->price, // ¡AQUÍ ESTÁ LA MAGIA! Precio real de la BD
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

            // Eliminamos usando la clave única (ej: 14-2)
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

                // --- MAGIA AJAX DE LAURITA ---
                // Si la petición viene por JavaScript, devolvemos solo las matemáticas
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

        // Si no es AJAX, recarga normal por si acaso
        return redirect()->back();
    }
}
