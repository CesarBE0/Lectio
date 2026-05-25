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

        // 🚀 Leemos la orden directamente desde la URL
        $isRemoveOnly = $request->query('action') === 'remove_only';

        // Hacemos el borrado fulminante usando el Query Builder puro
        $deletedRows = \Illuminate\Support\Facades\DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        if ($isRemoveOnly) {
            // 🔒 CERROJO DEL CONTROLADOR: Si viene de la Lista de Deseos,
            // PROHIBIMOS crear el libro de nuevo, pase lo que pase.
            $isWished = false;
            $message = 'Libro eliminado definitivamente.';
        } else {
            // Lógica normal para el botón de corazón del Catálogo
            if ($deletedRows > 0) {
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
