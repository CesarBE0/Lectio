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

        // 🚀 Usamos el Query del propio Modelo.
        // Esto garantiza que ataquemos a la tabla EXACTA que usas para pintar la pantalla.
        $deletedRows = Wishlist::query()
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        if ($deletedRows === 0 && $isRemoveOnly) {
            // Si falla, le pedimos al Modelo que nos enseñe qué hay realmente guardado
            $registrosReales = Wishlist::query()->where('user_id', $userId)->get();

            return response()->json([
                'success' => false,
                'message' => "Error: El botón envía el ID {$bookId}, pero no coincide con tu base de datos.",
                'debug_info' => [
                    'intentando_borrar_book_id' => $bookId,
                    'tus_filas_reales_segun_el_modelo' => $registrosReales
                ]
            ], 422);
        }

        if ($isRemoveOnly) {
            return response()->json([
                'success' => true,
                'is_wished' => false,
                'message' => 'Libro eliminado definitivamente.'
            ]);
        }

        // Lógica normal para el catálogo
        if ($deletedRows > 0) {
            $isWished = false;
            $message = 'Libro eliminado de tu lista de deseos.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'book_id' => $bookId
            ]);
            $isWished = true;
            $message = '¡Libro guardado en tu lista de deseos!';
        }

        return response()->json([
            'success' => true,
            'is_wished' => $isWished,
            'message' => $message
        ]);
    }
}
