<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BookController extends Controller
{
    public function show($id)
    {
        $book = \App\Models\Book::with('formats')->findOrFail($id);

        // Reseñas simuladas (hardcoded) para el diseño
        $reviews = [
            ['user' => 'Sarah Mitchell', 'date' => 'Jan 15, 2026', 'rating' => 5, 'text' => 'Una meditación absolutamente hermosa sobre la vida.'],
            ['user' => 'James Chen', 'date' => 'Jan 10, 2026', 'rating' => 4, 'text' => 'Una lectura que invita a la reflexión.'],
            ['user' => 'Emily Rodríguez', 'date' => 'Jan 5, 2026', 'rating' => 5, 'text' => 'Uno de los libros más profundos que he leído.'],
        ];

        return view('books.show', compact('book', 'reviews'));
    }
}
