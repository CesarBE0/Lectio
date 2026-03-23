<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->books();

        $filter = $request->query('filter');

        // Solo mantenemos el filtro de favoritos
        if ($filter == 'favorites') {
            $query->wherePivot('is_favorite', true);
        }

        $books = $query->get();

        // Estadísticas limpias
        $stats = (object)[
            'total'     => $user->books()->count(),
            'favorites' => $user->books()->wherePivot('is_favorite', true)->count(),
        ];

        return view('library.index', compact('books', 'stats', 'filter'));
    }

    public function toggleFavorite($id)
    {
        $user = Auth::user();
        // Buscamos el libro en la biblioteca del usuario
        $book = $user->books()->where('book_id', $id)->first();

        if ($book) {
            // Invertimos el estado de favorito (si era 1 pasa a 0, y viceversa)
            $newStatus = !$book->pivot->is_favorite;
            $user->books()->updateExistingPivot($id, ['is_favorite' => $newStatus]);

            return response()->json(['success' => true, 'is_favorite' => $newStatus]);
        }

        return response()->json(['success' => false], 404);
    }
}
