<?php

use App\Models\User;
use App\Models\Book;
use App\Models\Format;

test('Un usuario normal no puede acceder al panel de administración', function () {
    // Creamos un usuario normal (sin rol de admin)
    $user = User::factory()->create(['role' => 'user']);

    // Intentamos entrar a las rutas protegidas por el middleware 'admin'
    $this->actingAs($user)->get('/dashboard')->assertStatus(403); // 403 significa "Prohibido"
    $this->actingAs($user)->get('/inventario')->assertStatus(403);
});

test('El sistema bloquea el intento de añadir cantidades negativas al carrito', function () {
    $user = User::factory()->create();
    $book = Book::factory()->has(Format::factory()->state(['price' => 20]))->create();

    // Intentamos inyectar un POST con una cantidad tramposa
    $response = $this->actingAs($user)->post("/carrito/add/{$book->id}", [
        'quantity' => -5
    ]);

    // El sistema debe rechazarlo (generalmente con un error de validación 302)
    $response->assertSessionHasErrors('quantity');
    $this->assertFalse(session()->has('cart')); // El carrito debe seguir vacío
});

test('No se puede añadir al carrito más stock del disponible', function () {
    $user = User::factory()->create();
    // Creamos un libro que SOLO tiene 2 unidades en stock
    $book = Book::factory()->has(Format::factory()->state(['stock' => 2]))->create();

    // Intentamos comprar 5
    $response = $this->actingAs($user)->post("/carrito/add/{$book->id}", [
        'quantity' => 5
    ]);

    // Debe dar un error avisando al usuario y no añadir las 5 unidades
    $response->assertSessionHas('error');
    // (Asegúrate de que tu CartController comprueba el stock antes de añadir)
});

test('La API de búsqueda devuelve resultados en formato JSON', function () {
    // Creamos un libro específico
    Book::factory()->create(['title' => 'El Código Da Vinci']);

    // Hacemos una petición a tu ruta de búsqueda pasándole la query
    $response = $this->getJson('/api/search?q=Da Vinci');

    // Comprobamos que responde con un 200 OK y que el título está en el JSON
    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'El Código Da Vinci']);
});
