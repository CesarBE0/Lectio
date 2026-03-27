<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ofertas del mes
        $descuentos = \App\Models\Book::whereNotNull('discount_percent')->with('formats')->get();

        // 2. Top Ventas Definitivo (Sumando la cantidad real comprada)
        $populares = collect();

        try {
            $populares = \App\Models\Book::has('libraryEntries')
                ->withSum('libraryEntries', 'quantity') // Suma las unidades vendidas
                ->orderByDesc('library_entries_sum_quantity') // Ordena de mayor a menor
                ->take(4)
                ->get();
        } catch (\Exception $e) {
            // Protección contra errores
        }

        return view('home', compact('descuentos', 'populares'));
    }
}
