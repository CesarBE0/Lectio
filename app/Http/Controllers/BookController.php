<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // 👈 Añadimos Cache

class BookController extends Controller
{
    public function show($id)
    {
        // 1. Cacheamos el libro principal y sus formatos por 10 minutos
        $book = Cache::remember("book_{$id}", 600, function () use ($id) {
            return Book::with('formats')->findOrFail($id);
        });

        // 2. Comprobar si está en la Wishlist
        // 🛑 (Esto NO se cachea porque depende del usuario logueado en ese momento)
        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('book_id', $id)->exists();
        }

        // 3. Cacheamos las recomendaciones del libro por 10 minutos
        $recommended = Cache::remember("book_{$id}_recommendations", 600, function () use ($id) {
            $userIds = DB::table('library')->where('book_id', $id)->pluck('user_id');

            if($userIds->count() > 0) {
                return Book::with('formats')
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
            return collect(); // Devuelve colección vacía si no hay recomendaciones
        });

        return view('books.show', compact('book', 'recommended', 'inWishlist'));
    }
}
