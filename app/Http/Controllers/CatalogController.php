<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // 👈 IMPORTANTE: Añadimos la fachada de Cache

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Generamos una llave única para esta búsqueda combinando todos los filtros y la página actual
        $cacheKey = 'catalog_' . md5(json_encode($request->all()));

        // 2. Usamos Cache::remember. Si existe en Redis, lo devuelve al instante.
        // Si no, hace la consulta a BD, la guarda por 10 minutos (600 segundos) y la devuelve.
        $books = Cache::remember($cacheKey, 600, function () use ($request) {
            $query = Book::with('formats');

            // Filtro de Búsqueda
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('author', 'LIKE', "%{$search}%");
                });
            }

            // Filtro de Categoría
            if ($request->filled('category')) {
                $query->where('category', $request->input('category'));
            }

            // Filtro de Precio
            if ($request->filled('min_price')) {
                $query->whereHas('formats', function ($q) use ($request) {
                    $q->where('price', '>=', $request->input('min_price'));
                });
            }
            if ($request->filled('max_price')) {
                $query->whereHas('formats', function ($q) use ($request) {
                    $q->where('price', '<=', $request->input('max_price'));
                });
            }

            // Filtro de Páginas
            if ($request->filled('min_pages')) $query->where('pages', '>=', $request->input('min_pages'));
            if ($request->filled('max_pages')) $query->where('pages', '<=', $request->input('max_pages'));

            // Paginación
            return $query->paginate(12)->withQueryString();
        });

        // 3. Cacheamos también las categorías (cambian muy poco, ahorramos otra consulta)
        $categories = Cache::remember('catalog_categories', 600, function () {
            return Book::select('category')->distinct()->whereNotNull('category')->pluck('category');
        });

        return view('catalogo', compact('books', 'categories'));
    }
}
