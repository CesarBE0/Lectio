<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller {

    public function dashboard(Request $request)
    {
        $periodo = $request->get('periodo', 'todos');

        // Dashboard basado en la tabla library (compras reales)
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

        $subquery = DB::table('library')
            ->join('users', 'library.user_id', '=', 'users.id')
            ->select(
                'library.order_number',
                'users.name as user_name',
                DB::raw('MAX(library.created_at) as created_at'),
                DB::raw('SUM(library.price - library.discount + library.shipping) as totalPrice')
            )
            ->whereNotNull('library.order_number')
            ->groupBy('library.order_number', 'users.name');

        $query = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->select('*', DB::raw("CASE
                WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 48 THEN 'preparando'
                WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 96 THEN 'de_camino'
                ELSE 'entregado'
            END as status"));

        if ($statusFilter !== 'todos') {
            $query->whereRaw("CASE
                WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 48 THEN 'preparando'
                WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 96 THEN 'de_camino'
                ELSE 'entregado'
            END = ?", [$statusFilter]);
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
            // AÑADIMOS: address y city a la selección
            ->select('books.title', 'library.format as format_type', 'library.price', 'library.discount', 'library.shipping', 'users.name as user_name', 'library.created_at', 'library.address', 'library.city')
            ->get();

        if ($items->isEmpty()) return response()->json(['error' => 'No encontrado'], 404);

        $horas = Carbon::parse($items->first()->created_at)->diffInHours(now());
        $status = $horas <= 48 ? 'preparando' : ($horas <= 96 ? 'de_camino' : 'entregado');

        return response()->json([
            'user' => ['name' => $items->first()->user_name],
            // ENVIAMOS: Dirección y Ciudad al modal
            'address' => $items->first()->address,
            'city' => $items->first()->city,
            'status' => $status,
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

    // 1. Mostrar el formulario de creación
    public function createBook()
    {
        return view('admin.books.create');
    }

    // 2. Mostrar el formulario de edición con los datos del libro
    public function editBook($id)
    {
        // Buscamos el libro con sus formatos para que aparezcan en los campos
        $book = \App\Models\Book::with('formats')->findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function storeBook(Request $request) {
        // Añadimos old_price y discount_percent a la creación
        $book = Book::create($request->only([
            'title', 'author', 'image_url', 'description', 'old_price', 'discount_percent'
        ]));

        foreach ($request->formats as $type => $data) {
            $book->formats()->create([
                'type' => $type,
                'price' => $data['price'],
                'stock' => 0, // Como pediste, siempre inicia en 0
            ]);
        }
        return redirect()->route('admin.inventory')->with('success', 'Libro añadido con éxito al catálogo.');
    }

    public function updateBook(Request $request, $id)
    {
        $book = \App\Models\Book::findOrFail($id);

        // 1. Obtenemos el porcentaje del formulario (ej: 20)
        $percent = intval($request->discount_percentage);

        // 2. Actualizamos datos generales
        $book->update($request->only(['title', 'author', 'image_url', 'description']));

        // 3. Lógica de Marketing (Etiqueta roja y Precio tachado)
        if ($percent > 0) {
            $book->discount_percent = "-" . $percent . "%";
            // El precio tachado ("old_price") será el precio original del formato principal
            $book->old_price = $request->formats['Tapa dura']['price'] ?? 0;
        } else {
            $book->discount_percent = null;
            $book->old_price = null;
        }
        $book->save();

        // 4. Actualizamos los formatos aplicando el descuento al vuelo
        foreach ($request->formats as $type => $data) {
            $originalPrice = floatval($data['price']);

            // Calculamos el precio final: si hay 20%, el precio es el 80% del original
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

}
