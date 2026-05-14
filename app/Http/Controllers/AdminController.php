<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminController extends Controller {

    public function dashboard(Request $request)
    {
        $periodo = $request->get('periodo', 'todos');

        $queryBase = DB::table('library')->whereNotNull('order_number');

        if ($periodo == '7_dias') { $queryBase->where('created_at', '>=', now()->subDays(7)); }
        if ($periodo == '30_dias') { $queryBase->where('created_at', '>=', now()->subDays(30)); }

        $stats = [
            'total_sales' => (clone $queryBase)->sum(DB::raw('price - discount + shipping')),
            'orders_count' => (clone $queryBase)->distinct('order_number')->count(),
            'inventory_count' => Book::count(),
            'clients_count' => User::where('role', 'user')->count(),
        ];

        $ventasMensuales = DB::table('library')
            ->selectRaw('MONTH(created_at) as mes, SUM(price - discount + shipping) as total')
            ->groupBy('mes')->orderBy('mes')
            ->pluck('total', 'mes')->all();

        $chartVentasData = [];
        for ($i = 1; $i <= 12; $i++) { $chartVentasData[] = $ventasMensuales[$i] ?? 0; }

        $formatStats = DB::table('library')
            ->select('format as type', DB::raw('count(*) as total'))
            ->groupBy('type')->get();

        return view('admin.dashboard', compact('stats', 'chartVentasData', 'formatStats', 'periodo'));
    }

    public function inventory(Request $request)
    {
        $search = $request->input('search');
        $books = Book::with('formats')
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('admin.inventory', compact('books'));
    }

    public function orders(Request $request)
    {
        $statusFilter = $request->input('status', 'todos');

        // Subquery: Agrupamos por pedido y traemos el estado real (status)
        $subquery = DB::table('library')
            ->join('users', 'library.user_id', '=', 'users.id')
            ->select(
                'library.order_number',
                'users.name as user_name',
                DB::raw('MAX(library.created_at) as created_at'),
                DB::raw('SUM(library.price - library.discount + library.shipping) as totalPrice'),
                // Seleccionamos el estado real (cogemos el MAX por si hay ligeras variaciones, aunque todos deberían ser iguales por pedido)
                DB::raw('MAX(library.status) as status')
            )
            ->whereNotNull('library.order_number')
            ->groupBy('library.order_number', 'users.name');

        // Construimos la query principal sobre la subquery
        $query = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->select('*');

        // Aplicamos el filtro si el administrador ha seleccionado uno
        if ($statusFilter !== 'todos') {
            $query->where('status', '=', $statusFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.orders', compact('orders', 'statusFilter'));
    }

    public function getOrderDetails($orderNumber)
    {
        $items = DB::table('library')
            ->join('books', 'library.book_id', '=', 'books.id')
            ->join('users', 'library.user_id', '=', 'users.id')
            ->where('library.order_number', $orderNumber)
            ->select('books.title', 'library.format as format_type', 'library.price', 'library.discount', 'library.shipping', 'users.name as user_name', 'library.created_at', 'library.address', 'library.city', 'library.status')
            ->get();

        if ($items->isEmpty()) return response()->json(['error' => 'No encontrado'], 404);

        // AHORA SÍ: Usamos el estado que viene de la base de datos, no el cálculo de horas.
        $statusReal = $items->first()->status;

        return response()->json([
            'user' => ['name' => $items->first()->user_name],
            'address' => $items->first()->address,
            'city' => $items->first()->city,
            'status' => $statusReal, // <-- ¡Pasamos el estado real al modal!
            'totalPrice' => $items->sum(fn($i) => $i->price - $i->discount + $i->shipping),
            'order_items' => $items->map(fn($i) => [
                'book' => ['title' => $i->title],
                'format_type' => $i->format_type,
                'price' => number_format($i->price - $i->discount + $i->shipping, 2, '.', ''),
                'quantity' => 1
            ])
        ]);
    }

    public function destroyOrder($orderNumber)
    {
        DB::table('library')->where('order_number', $orderNumber)->delete();
        return back()->with('success', 'Pedido eliminado del historial.');
    }

    public function destroyBook($id) {
        Book::findOrFail($id)->delete();
        return back()->with('success', 'Libro eliminado.');
    }

    public function createBook()
    {
        return view('admin.books.create');
    }

    public function editBook($id)
    {
        $book = \App\Models\Book::with('formats')->findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function storeBook(Request $request)
    {
        // 1. Validamos todos los campos que vienen del nuevo formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string',
            'pages' => 'required|integer',
            'synopsis' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'formats' => 'required|array'
        ]);

        $imageName = null;

        // 2. Comprobamos si hay foto y le damos el formato perfecto (Ej: Nuevo_libro.png)
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $cleanTitle = Str::slug($request->title, '_');
            $cleanTitle = ucfirst($cleanTitle);

            $extension = $file->getClientOriginalExtension();
            $imageName = $cleanTitle . '.' . $extension;

            // Movemos la imagen a la carpeta public/img
            $file->move(public_path('img'), $imageName);
        }

        // 3. Tomamos el precio del formato 'Tapa dura' como precio base del libro
        $basePrice = $request->formats['Tapa dura']['price'] ?? 0;

        // 4. Creamos el libro en la base de datos
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'pages' => $request->pages,
            'synopsis' => $request->synopsis,
            'image_url' => $imageName,
            'price' => $basePrice,
            'is_bestseller' => false,
        ]);

        // 5. Guardamos las relaciones de los formatos (Tapa dura, E-book, Audiolibro)
        foreach ($request->formats as $type => $data) {
            $book->formats()->create([
                'type' => $type,
                'price' => $data['price'],
                'stock' => $data['stock'] ?? 0,
            ]);
        }

        return redirect()->route('admin.inventory')->with('success', '¡Libro y portada añadidos con éxito al catálogo!');
    }

    public function updateBook(Request $request, $id)
    {
        $book = \App\Models\Book::findOrFail($id);

        $percent = intval($request->discount_percentage);

        $book->update($request->only(['title', 'author', 'image_url', 'synopsis']));

        if ($percent > 0) {
            $book->discount_percent = "-" . $percent . "%";
            $book->old_price = $request->formats['Tapa dura']['price'] ?? 0;
        } else {
            $book->discount_percent = null;
            $book->old_price = null;
        }
        $book->save();

        foreach ($request->formats as $type => $data) {
            $originalPrice = floatval($data['price']);

            $finalPrice = $percent > 0 ? ($originalPrice * (1 - ($percent / 100))) : $originalPrice;

            $book->formats()->updateOrCreate(
                ['type' => $type],
                [
                    'price' => round($finalPrice, 2),
                    'stock' => $data['stock'] ?? 0,
                ]
            );
        }

        return redirect()->route('admin.inventory')->with('success', 'Libro y ofertas actualizados con éxito.');
    }

    public function updateStatus(\Illuminate\Http\Request $request, $orderNumber)
    {
        // Usamos update() directamente sobre la tabla library
        $affected = \Illuminate\Support\Facades\DB::table('library')
            ->where('order_number', $orderNumber)
            ->update(['status' => $request->input('status')]);

        if ($affected > 0) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No se encontró el pedido o el estado es el mismo']);
    }

}
