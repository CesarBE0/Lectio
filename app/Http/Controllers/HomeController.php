<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        // Usamos with('formats') para traer los precios de una vez
        $descuentos = Book::with('formats')->whereNotNull('discount_percent')->take(4)->get();
        $populares = Book::with('formats')->where('is_bestseller', true)->take(4)->get();

        $opiniones = [
            ['texto' => 'Una web familiar, encuentro todos mis libros favoritos a muy buen precio.', 'autor' => 'Luna F.'],
            ['texto' => 'El catálogo es enorme y el servicio rápido. Recomiendo 100%.', 'autor' => 'Kevin G.'],
            ['texto' => 'Me encanta poder recopilar clásicos y nuevas lecturas todo en el mismo sitio.', 'autor' => 'María C.']
        ];

        return view('home', compact('descuentos', 'populares', 'opiniones'));
    }
}
