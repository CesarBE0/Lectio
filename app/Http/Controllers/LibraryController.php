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

        if ($filter == 'favorites') {
            $query->wherePivot('is_favorite', true);
        }

        $books = $query->orderByPivot('created_at', 'desc')->get();

        $stats = (object)[
            'total'     => $user->books()->count(),
            'favorites' => $user->books()->wherePivot('is_favorite', true)->count(),
        ];

        return view('library.index', compact('books', 'stats', 'filter'));
    }

    public function toggleFavorite($id)
    {
        $user = Auth::user();
        $book = $user->books()->where('book_id', $id)->first();

        if ($book) {
            $newStatus = !$book->pivot->is_favorite;
            $user->books()->updateExistingPivot($id, ['is_favorite' => $newStatus]);

            return response()->json(['success' => true, 'is_favorite' => $newStatus]);
        }

        return response()->json(['success' => false], 404);
    }

    public function read(Book $book)
    {
        // 1. Validar que el usuario sea dueño del libro
        $hasBook = auth()->user()->books()->where('book_id', $book->id)->exists();

        if (!$hasBook) {
            return redirect()->route('library.index')->with('error', 'No tienes acceso a este libro.');
        }

        // 2. Ruta al archivo privado
        $path = storage_path('app/private/pdfs/' . $book->pdf_path);

        if (!file_exists($path)) {
            abort(404, 'El archivo no se encuentra en el servidor.');
        }

        // 3. Devolver el PDF para que se abra en el navegador
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $book->title . '.pdf"'
        ]);
    }
}
