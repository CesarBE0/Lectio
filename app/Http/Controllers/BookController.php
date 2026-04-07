<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function show($id)
    {
        $book = Book::with('formats')->findOrFail($id);

        // 1. Comprobar si está en la Wishlist
        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('book_id', $id)->exists();
        }

        // 2. Recomendaciones (También te puede interesar)
        $userIds = DB::table('library')->where('book_id', $id)->pluck('user_id');

        $recommended = collect();
        if($userIds->count() > 0) {
            // 👇 ¡Añadimos with('formats') aquí para cargar los precios rápido! 👇
            $recommended = Book::with('formats')
                ->select('books.*')
                ->join('library', 'books.id', '=', 'library.book_id')
                ->whereIn('library.user_id', $userIds)
                ->where('books.id', '!=', $id)
                ->selectRaw('COUNT(library.book_id) as total_buys')
                ->groupBy('books.id')
                ->orderByDesc('total_buys')
                ->take(4)
                ->get();
        }

        return view('books.show', compact('book', 'recommended', 'inWishlist'));
    }
}
