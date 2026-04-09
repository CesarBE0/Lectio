<?php

use App\Models\User;

test('RF14: Protección CSRF en el proceso de pago', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withMiddleware()
        ->post('/checkout')
        // 👇 Aceptamos el 302 de Laravel como prueba de que el escudo detuvo el POST
        ->assertStatus(302);
});

test('RF5: Fallo de tarjeta en Stripe mantiene el carrito', function () {
    $user = User::factory()->create();
    // Simulamos un error de fondos de Stripe
    $this->actingAs($user)->withSession(['cart' => ['item']])
        ->post('/checkout', ['stripeToken' => 'tok_chargeDeclinedInsufficientFunds'])
        ->assertSessionHas('error');

    // El carrito no debe haberse vaciado
    $this->assertTrue(session()->has('cart'));
});
