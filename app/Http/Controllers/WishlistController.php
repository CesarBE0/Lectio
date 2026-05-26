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

        // 🚀 BORRADO DIRECTO Y ABSOLUTO: Atacamos directamente a la tabla por sus dos columnas clave
        // Esto ignora cualquier problema de configuración del Modelo y limpia la fila en DBeaver siempre
        $deletedRows = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        // Si la petición viene de la vista de la Lista de Deseos (Remove Only)
        if ($isRemoveOnly) {
            // 🔒 Al retornar aquí inmediatamente, garantizamos que el libro JAMÁS
            // se vuelva a duplicar o recrear por culpa de un doble clic de JavaScript
            return response()->json([
                'success' => true,
                'is_wished' => false,
                'message' => 'Libro eliminado definitivamente de la base de datos.'
            ]);
        }

        // Lógica normal para el botón de corazón del Catálogo (Toggle tradicional)
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
