<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Cargamos el libro junto con sus formatos
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

        // Filtro de Precio (AHORA BUSCA EN LOS FORMATOS)
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
        $books = $query->paginate(12)->withQueryString();

        $categories = Book::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('catalogo', compact('books', 'categories'));
    }
}
