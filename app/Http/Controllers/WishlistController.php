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

        // 🚀 Detectamos la orden estricta de borrado
        $isRemoveOnly = $request->input('action') === 'remove_only' || $request->query('action') === 'remove_only';

        if ($isRemoveOnly) {
            // 🔒 GUILLOTINA ABSOLUTA: Borra el registro y CORTA el código inmediatamente.
            DB::table('wishlists')
                ->where('user_id', $userId)
                ->where('book_id', $bookId)
                ->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'is_wished' => false,
                    'message' => 'Libro eliminado definitivamente.'
                ]);
            }
            return back()->with('success', 'Libro eliminado.');
        }

        // --- Lógica normal de Interruptor (Solo para los corazones del Catálogo) ---
        $deletedRows = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        if ($deletedRows > 0) {
            $isWished = false;
            $message = 'Libro eliminado de tu lista de deseos.';
        } else {
            DB::table('wishlists')->insert([
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
