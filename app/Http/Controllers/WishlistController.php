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

        // 🚀 Leemos la acción desde el Body JSON (input) que es 100% seguro contra redirecciones
        $isRemoveOnly = $request->input('action') === 'remove_only' || $request->query('action') === 'remove_only';

        // 🔒 GUILLOTINA ABSOLUTA: Si viene de la Lista de Deseos, borra y CORTA la ejecución
        if ($isRemoveOnly) {
            \Illuminate\Support\Facades\DB::table('wishlists')
                ->where('user_id', $userId)
                ->where('book_id', $bookId)
                ->delete();

            return response()->json([
                'success' => true,
                'is_wished' => false,
                'message' => 'Libro eliminado definitivamente.'
            ]);
        }

        // --- Lógica de Interruptor (Solo para el Catálogo) ---
        $deletedRows = \Illuminate\Support\Facades\DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        if ($deletedRows > 0) {
            $isWished = false;
            $message = 'Libro eliminado de tu lista de deseos.';
        } else {
            \Illuminate\Support\Facades\DB::table('wishlists')->insert([
                'user_id'    => $userId,
                'book_id'    => $bookId,
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
