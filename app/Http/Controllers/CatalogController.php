<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Creamos una clave de caché única basada en los filtros aplicados
        $cacheKey = 'catalog_' . md5(json_encode($request->all()));

        $books = Cache::remember($cacheKey, 600, function () use ($request) {
            $query = Book::with('formats');

            // Búsqueda por título o autor en toda la base de datos
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('author', 'LIKE', "%{$search}%");
                });
            }

            // Filtro por categoría/género
            if ($request->filled('category')) {
                $query->where('category', $request->input('category'));
            }

            // Filtros de precio
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

            // Filtros de páginas
            if ($request->filled('min_pages')) $query->where('pages', '>=', $request->input('min_pages'));
            if ($request->filled('max_pages')) $query->where('pages', '<=', $request->input('max_pages'));

            // Importante: withQueryString() mantiene los filtros al cambiar de página
            return $query->paginate(12)->withQueryString();
        });

        // Obtenemos las categorías existentes para el selector del Aside
        $categories = Cache::remember('catalog_categories', 600, function () {
            return Book::select('category')->distinct()->whereNotNull('category')->pluck('category');
        });

        return view('catalogo', compact('books', 'categories'));
    }
}
