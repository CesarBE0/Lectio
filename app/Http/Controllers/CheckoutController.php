<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
// Asumo que tienes un modelo Cart o usas una sesión para el carrito
// Para este ejemplo, simularemos que procesamos los items del carrito

class CheckoutController extends Controller
{
    public function index()
    {
        // Recuperamos tu carrito (en formato array como vimos antes)
        $cartItems = session()->get('cart', []);

        // 1. Calculamos el SUBTOTAL sumando el precio de los libros
        $subtotal = 0;
        foreach($cartItems as $item) {
            $subtotal += $item['price'] * ($item['quantity'] ?? 1);
        }

        // 2. Lógica de Envío Premium: ¿Llega a 50€?
        // Si el subtotal es mayor o igual a 50, el envío es 0. Si no, cobramos 4.90€
        $shipping = $subtotal >= 50 ? 0 : 4.99;

        // 3. El TOTAL REAL que se le va a cobrar a la tarjeta
        $total = $subtotal + $shipping;

        // Pasamos todas estas variables a la vista
        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        // 1. Configurar la clave secreta
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // 2. Crear el cargo (en céntimos, 4590 = 45.90€)
            $charge = Charge::create([
                'amount' => 4590,
                'currency' => 'eur',
                'description' => 'Compra en Lectio - Usuario: ' . Auth::user()->email,
                'source' => $request->stripeToken, // El token que genera el JS
            ]);

            // 3. SI EL PAGO TIENE ÉXITO: Registramos los libros en la biblioteca
            $user = Auth::user();
            // ... (aquí el código de attach/sync que hicimos antes) ...

            return redirect()->route('library.index')->with('success', '¡Pago confirmado!');

        } catch (\Exception $e) {
            // Si el pago falla (tarjeta denegada, etc.)
            return back()->withErrors('Error en el pago: ' . $e->getMessage());
        }
    }
}
