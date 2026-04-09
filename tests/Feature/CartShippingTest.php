<?php

use App\Models\Book;
use App\Models\Format;
use App\Models\User;

test('RF3 & RF8 & RF9: Gestión completa del carrito', function () {
    $user = User::factory()->create();
    $book = Book::factory()->has(Format::factory()->state(['price' => 20]))->create();

    // RF3: Añadir al carrito
    $this->actingAs($user)->post("/carrito/add/{$book->id}")->assertStatus(302);
    // Comprobamos que el carrito existe en la sesión (en lugar de buscar 'cart_count')
    $this->assertTrue(session()->has('cart'));

    // RF9: Vaciar carrito completo usando tu ruta real
    $this->actingAs($user)->get("/vaciar-carrito")->assertStatus(302);
    // Comprobamos que el carrito ya no existe
    $this->assertFalse(session()->has('cart'));
});

test('RF4: Cálculo dinámico de gastos de envío', function () {
    // Caso < 30€
    $response = $this->withSession(['cart' => [['price' => 20]]])->get('/carrito');
    $response->assertSee('Envío: 4.99'); // O el precio que tengas

    // Caso > 30€
    $response = $this->withSession(['cart' => [['price' => 50]]])->get('/carrito');
    $response->assertSee('Envío gratuito');
});
