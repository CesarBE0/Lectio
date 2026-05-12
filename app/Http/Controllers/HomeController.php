<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        $descuentos = \App\Models\Book::whereNotNull('discount_percent')->with('formats')->get();
        $populares = collect();

        try {
            $populares = \App\Models\Book::has('libraryEntries')
                ->withSum('libraryEntries', 'quantity')
                ->orderByDesc('library_entries_sum_quantity')
                ->take(4)
                ->get();
        } catch (\Exception $e) {
        }

        return view('home', compact('descuentos', 'populares'));
    }
}
