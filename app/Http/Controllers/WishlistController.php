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

        $deletedRows = Wishlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        // 🚀 EL BLINDAJE: Si la orden viene de la página de Wishlist,
        // prohibimos terminantemente que el libro se vuelva a crear.
        if ($request->input('action') === 'remove_only') {
            return response()->json([
                'success' => true,
                'is_wished' => false,
                'message' => 'Libro eliminado definitivamente.'
            ]);
        }

        // Lógica normal para el Catálogo (donde sí queremos que sea un interruptor)
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
