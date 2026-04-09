<?php

use App\Models\User;
use App\Models\Book;

test('RF6: Alternar favoritos con el icono de la estrella', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $user->books()->attach($book->id, ['is_favorite' => false]);

    // Encender estrella
    $this->actingAs($user)->postJson("/biblioteca/favorito/{$book->id}");
    $this->assertDatabaseHas('library', ['book_id' => $book->id, 'is_favorite' => true]);

    // Apagar estrella
    $this->actingAs($user)->postJson("/biblioteca/favorito/{$book->id}");
    $this->assertDatabaseHas('library', ['book_id' => $book->id, 'is_favorite' => false]);
});

test('RF10: Vista de Biblioteca muestra stats correctas', function () {
    $user = User::factory()->create();
    $books = Book::factory()->count(2)->create();
    $user->books()->attach($books->pluck('id'), ['is_favorite' => true]);

    $this->actingAs($user)->get('/biblioteca')
        ->assertSee('2') // Libros totales
        ->assertSee('Favoritos');
});
