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

        // Hacemos el borrado asegurando la tabla correcta con el Modelo
        $deletedRows = Wishlist::query()
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        if ($isRemoveOnly) {
            // 🚀 LA MAGIA DE LA IDEMPOTENCIA:
            // Si borró 1 fila (primer clic) o si borró 0 porque ya no existía (clic fantasma duplicado),
            // el resultado es el que buscamos: el libro ya no está. Devolvemos éxito siempre.
            return response()->json([
                'success' => true,
                'is_wished' => false,
                'message' => 'Libro eliminado definitivamente.'
            ]);
        }

        // Lógica normal para el botón de corazón del Catálogo
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
