<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function dashboard(Request $request)
    {
        // Capturamos los filtros (Tiempo y Estado)
        $periodo = $request->get('periodo', 'todos');
        $status = $request->get('status', 'completed'); // Por defecto miramos lo vendido

        $queryOrders = \App\Models\Order::query();

        // Filtro de fecha
        if ($periodo == '7_dias') {
            $queryOrders->where('created_at', '>=', now()->subDays(7));
        } elseif ($periodo == '30_dias') {
            $queryOrders->where('created_at', '>=', now()->subDays(30));
        }

        // Estadísticas rápidas
        $stats = [
            'total_sales' => (clone $queryOrders)->where('status', 'completed')->sum('totalPrice'),
            'orders_count' => (clone $queryOrders)->count(),
            'inventory_count' => \App\Models\Book::count(),
            'clients_count' => \App\Models\User::where('role', 'user')->count(),
        ];

        // --- GRÁFICO 1: VENTAS MENSUALES ---
        $ventasMensuales = \App\Models\Order::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as mes, SUM(totalPrice) as total')
            ->groupBy('mes')->orderBy('mes')
            ->pluck('total', 'mes')->all();

        $chartVentasData = [];
        for ($i = 1; $i <= 12; $i++) { $chartVentasData[] = $ventasMensuales[$i] ?? 0; }

        // --- GRÁFICO 2: DISTRIBUCIÓN POR FORMATO (Basado en Pedidos) ---
        // Aquí contamos qué formatos se han vendido en los pedidos filtrados
        $formatStats = \DB::table('order_items') // Cambia 'order_items' por el nombre de tu tabla de detalles
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.id', (clone $queryOrders)->pluck('id')) // Aplicamos el mismo filtro de fecha
            ->select('order_items.format_type as type', \DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        $recentOrders = $queryOrders->with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'chartVentasData', 'formatStats', 'periodo'));
    }

    public function inventory() {
        $books = Book::with('formats')->get();
        return view('admin.inventory', compact('books'));
    }

    public function orders(Request $request)
    {
        $status = $request->get('status', 'todos');
        $query = \App\Models\Order::with('user')->latest();

        if ($status != 'todos') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10); // Paginación de 10 en 10

        return view('admin.orders', compact('orders', 'status'));
    }
    public function getOrderDetails($id)
    {
        // Cargamos el pedido con el usuario y los items (libros y formatos)
        $order = \App\Models\Order::with(['user', 'orderItems.book'])->findOrFail($id);

        return response()->json($order);
    }

    public function storeBook(Request $request) {
        $book = Book::create($request->except('formats'));
        foreach ($request->formats as $type => $data) {
            $book->formats()->create([
                'type' => $type,
                'price' => $data['price'],
                'stock' => ($type === 'Tapa dura') ? ($data['stock'] ?? 0) : 0,
            ]);
        }
        return redirect()->route('admin.inventory')->with('success', 'Libro creado con éxito');
    }

    public function destroyBook($id) {
        Book::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Libro eliminado permanentemente');
    }

    public function destroyOrder($id)
    {
        // Buscamos el pedido
        $order = \App\Models\Order::findOrFail($id);

        // Lo eliminamos (esto también borrará los order_items si pusiste onDelete('cascade') en la migración)
        $order->delete();

        // Volvemos atrás con un mensaje de éxito
        return back()->with('success', 'El pedido ha sido eliminado del sistema de Lectio.');
    }
}
