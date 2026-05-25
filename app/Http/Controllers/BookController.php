<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    public function show($id)
    {
        $book = Cache::remember("book_{$id}", 600, function () use ($id) {
            return Book::with('formats')->findOrFail($id);
        });

        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('book_id', $id)->exists();
        }

        $recommended = Cache::remember("book_{$id}_recommendations", 600, function () use ($id) {
            $userIds = DB::table('user_library')->where('book_id', $id)->pluck('user_id');

            if($userIds->count() > 0) {
                return Book::with('formats')
                    ->select('books.*')
                    ->join('user_library', 'books.id', '=', 'library.book_id')
                    ->whereIn('user_library.user_id', $userIds)
                    ->where('books.id', '!=', $id)
                    ->selectRaw('COUNT(user_library.book_id) as total_buys')
                    ->groupBy('books.id')
                    ->orderByDesc('total_buys')
                    ->take(4)
                    ->get();
            }
            return collect();
        });

        return view('books.show', compact('book', 'recommended', 'inWishlist'));
    }
}
