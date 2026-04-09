<?php

use App\Models\User;

test('RF1: Registro completo de usuario con validaciones', function () {
    $response = $this->post('/register', [
        'name' => 'César Lectio',
        'email' => 'cesar@ejemplo.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', ['email' => 'cesar@ejemplo.com']);
    $this->assertAuthenticated();
});

test('RF2: Login con credenciales correctas e incorrectas', function () {
    $user = User::factory()->create(['password' => bcrypt('secret123')]);

    // Intento fallido
    $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors();

    // Intento correcto
    $this->post('/login', ['email' => $user->email, 'password' => 'secret123'])->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
});

test('RF7: El cierre de sesión destruye la sesión del usuario', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post('/logout')->assertRedirect('/');
    $this->assertGuest();
});

test('RF12: Protección contra Inyección SQL en el Login', function () {
    $response = $this->post('/login', [
        'email' => "' OR 1=1 --",
        'password' => 'cualquiera'
    ]);

    // El sistema debe fallar el login, no entrar
    $this->assertGuest();
});

test('RF13: Bloqueo de acceso a rutas privadas (/biblioteca)', function () {
    $this->get('/biblioteca')->assertRedirect('/login');
});
