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

        // 🚀 Ejecutamos el borrado directo en la tabla 'wishlists'
        $deletedRows = \Illuminate\Support\Facades\DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();

        // 🔍 SI INTENTABAS BORRAR PERO LA BASE DE DATOS DICE QUE BORRÓ 0 FILAS:
        if ($deletedRows === 0 && $isRemoveOnly) {
            // Sácame todo lo que este usuario tiene guardado realmente en la tabla
            $registrosReales = \Illuminate\Support\Facades\DB::table('wishlists')
                ->where('user_id', $userId)
                ->get();

            return response()->json([
                'success' => false,
                'message' => "Error: El libro ID {$bookId} no se ha podido borrar porque no existe en la tabla con tu user_id.",
                'debug_info' => [
                    'buscando_book_id' => $bookId,
                    'user_id_actual' => $userId,
                    'tus_filas_reales_en_db' => $registrosReales
                ]
            ], 422); // Enviamos un código de error para que Javascript lo detecte
        }

        if ($isRemoveOnly) {
            return response()->json([
                'success' => true,
                'is_wished' => false,
                'message' => 'Libro eliminado definitivamente.'
            ]);
        }

        // Lógica normal para el interruptor del Catálogo (Toggle)
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

        return response()->json([
            'success' => true,
            'is_wished' => $isWished,
            'message' => $message
        ]);
    }
}
