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

        // ✅ CORREGIDO: Forzamos a Laravel a cargar todas las columnas de la tabla intermedia
        $query = $user->books()->withPivot('is_favorite', 'format', 'order_number', 'address', 'city');

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

        // 1. Buscamos directamente en la tabla pivote 'library' para asegurar el valor real
        $pivot = \Illuminate\Support\Facades\DB::table('library')
            ->where('user_id', $user->id)
            ->where('book_id', $id)
            ->first();

        if ($pivot) {
            // 2. Invertimos el estado de forma segura (si es 1 pasa a 0, si es 0 a 1)
            $newStatus = !$pivot->is_favorite;

            $user->books()->updateExistingPivot($id, ['is_favorite' => $newStatus]);

            // 3. Soportamos ambos mundos: si el test pide HTML o si tu JavaScript pide JSON
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'is_favorite' => $newStatus]);
            }

            return redirect()->back()->with('success', 'Estado de favorito actualizado.');
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => false], 404);
        }

        abort(404);
    }

    public function read(Book $book)
    {
        $hasBook = auth()->user()->books()->where('book_id', $book->id)->exists();

        if (!$hasBook) {
            return redirect()->route('library.index')->with('error', 'No tienes acceso a este libro.');
        }

        $path = storage_path('app/private/pdfs/' . $book->pdf_path);

        if (!file_exists($path)) {
            // 🚀 CHIVATO ACTIVADO: Escáner del servidor
            dd([
                '1_MENSAJE' => '¡Te pillé! Laravel no encuentra el archivo físico en el disco.',
                '2_NOMBRE_EN_BD' => $book->pdf_path ?? '¡CUIDADO! Está guardado como NULL en la base de datos.',
                '3_RUTA_BUSCADA' => $path,
                '4_CARPETA_EXISTE' => is_dir(storage_path('app/private/pdfs')),
                '5_CONTENIDO_DE_CARPETA' => is_dir(storage_path('app/private/pdfs')) ? scandir(storage_path('app/private/pdfs')) : 'No existe la carpeta'
            ]);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $book->title . '.pdf"'
        ]);
    }

    public function streamAudio(Book $book)
    {
        $hasBook = auth()->user()->books()->where('book_id', $book->id)->exists();

        if (!$hasBook) {
            abort(403);
        }

        $path = storage_path('app/private/audios/' . $book->audio_path);

        if (!file_exists($path)) {
            // 🚀 CHIVATO ACTIVADO: Escáner del servidor
            dd([
                '1_MENSAJE' => '¡Te pillé! El audio no está físicamente en el disco.',
                '2_NOMBRE_EN_BD' => $book->audio_path ?? '¡CUIDADO! Guardado como NULL en la base de datos.',
                '3_RUTA_BUSCADA' => $path,
                '4_CONTENIDO_DE_CARPETA' => is_dir(storage_path('app/private/audios')) ? scandir(storage_path('app/private/audios')) : 'No existe'
            ]);
        }

        return response()->file($path, [
            'Content-Type' => 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="' . $book->title . '.mp3"'
        ]);
    }
}
