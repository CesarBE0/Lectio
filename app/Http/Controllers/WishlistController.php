<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::with('book')->where('user_id', Auth::id())->get();
        return view('wishlist.index', compact('wishlist'));
    }

    public function toggle(Request $request, $bookId)
    {
        $userId = Auth::id();

        // 🚀 Borramos todos los posibles duplicados de golpe usando Query Builder
        $deletedRows = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        if ($deletedRows > 0) {
            // Si borró al menos 1 fila, confirmamos que se ha quitado
            $isWished = false;
            $message = 'Libro eliminado de tu lista de deseos.';
        } else {
            // Si no borró nada, significa que no existía, así que lo creamos
            DB::table('wishlists')->insert([
                'user_id' => $userId,
                'book_id' => $bookId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $isWished = true;
            $message = '¡Libro guardado en tu lista de deseos!';
        }

        // Respuesta para AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_wished' => $isWished,
                'message' => $message
            ]);
        }

        // Respuesta tradicional (por si falla Javascript)
        return back()->with('success', $message);
    }
}
