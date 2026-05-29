<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Traemos los libros con descuento y precargamos sus formatos para evitar el problema N+1
        $descuentos = Book::whereNotNull('discount_percent')->with('formats')->get();
        $populares = collect();

        try {
            // 🚀 PASO 1: Buscamos directamente en la nueva tabla 'user_library' los 4 libros más vendidos
            $topBookIds = DB::table('user_library')
                ->select('book_id', DB::raw('COUNT(*) as total_ventas'))
                ->groupBy('book_id')
                ->orderByDesc('total_ventas')
                ->take(4)
                ->pluck('book_id');

            // 🚀 PASO 2: Si hay ventas, traemos esos libros desde el modelo Book
            if ($topBookIds->isNotEmpty()) {
                $populares = Book::with('formats')
                    ->whereIn('id', $topBookIds)
                    ->get()
                    // Ordenamos la colección final exactamente en el mismo orden de top ventas
                    ->sortBy(function($book) use ($topBookIds) {
                        return array_search($book->id, $topBookIds->toArray());
                    });
            }
        } catch (\Exception $e) {
            // Si ocurriese un error (por ejemplo, base de datos caída),
            // atrapamos el fallo para que la web siga cargando sin la sección en lugar de dar un pantallazo de error.
        }

        return view('home', compact('descuentos', 'populares'));
    }
}
