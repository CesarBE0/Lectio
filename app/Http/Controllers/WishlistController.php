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
        $isRemoveOnly = $request->query('action') === 'remove_only';

        // 1. Buscamos el registro exacto usando Eloquent
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        $deletedRows = 0;

        if ($wishlistItem) {
            // 🚀 LA CLAVE: Si lo encuentra, lo borramos usando su 'id' único de la tabla.
            // ¡Esto es 100% infalible en DBeaver!
            $deletedRows = \Illuminate\Support\Facades\DB::table('wishlists')
                ->where('id', $wishlistItem->id)
                ->delete();
        } else {
            // Plan B de rescate: Por si acaso intentamos el borrado tradicional por columnas
            $deletedRows = \Illuminate\Support\Facades\DB::table('wishlists')
                ->where('user_id', $userId)
                ->where('book_id', $bookId)
                ->delete();
        }

        // Si la petición viene de la vista de la Lista de Deseos (Remove Only)
        if ($isRemoveOnly) {
            return response()->json([
                'success' => true,
                'is_wished' => false,
                'message' => 'Libro eliminado definitivamente de la base de datos.'
            ]);
        }

        // Lógica normal para el botón de corazón del Catálogo (Toggle)
        if ($deletedRows > 0 || $wishlistItem) {
            $isWished = false;
            $message = 'Libro eliminado de tu lista de deseos.';
        } else {
            \Illuminate\Support\Facades\DB::table('wishlists')->insert([
                'user_id' => $userId,
                'book_id' => $bookId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $isWished = true;
            $message = '¡Libro guardado en tu lista de deseos!';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_wished' => $isWished,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
