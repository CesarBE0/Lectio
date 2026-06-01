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

        $queryBase = DB::table('user_library')->whereNotNull('order_number');

        if ($periodo == '7_dias') { $queryBase->where('created_at', '>=', now()->subDays(7)); }
        if ($periodo == '30_dias') { $queryBase->where('created_at', '>=', now()->subDays(30)); }

        $stats = [
            'total_sales' => (clone $queryBase)->sum(DB::raw('price - discount + shipping')),
            'orders_count' => (clone $queryBase)->distinct('order_number')->count(),
            'inventory_count' => Book::count(),
            'clients_count' => User::where('role', 'user')->count(),
        ];

        $ventasMensuales = DB::table('user_library')
            ->selectRaw('MONTH(created_at) as mes, SUM(price - discount + shipping) as total')
            ->groupBy('mes')->orderBy('mes')
            ->pluck('total', 'mes')->all();

        $chartVentasData = [];
        for ($i = 1; $i <= 12; $i++) { $chartVentasData[] = $ventasMensuales[$i] ?? 0; }

        $formatStats = DB::table('user_library')
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
        $subquery = DB::table('user_library')
            ->join('users', 'user_library.user_id', '=', 'users.id')
            ->select(
                'user_library.order_number',
                'users.name as user_name',
                DB::raw('MAX(user_library.created_at) as created_at'),
                DB::raw('SUM(user_library.price - user_library.discount + user_library.shipping) as totalPrice'),
                // Seleccionamos el estado real (cogemos el MAX por si hay ligeras variaciones, aunque todos deberían ser iguales por pedido)
                DB::raw('MAX(user_library.status) as status')
            )
            ->whereNotNull('user_library.order_number')
            ->groupBy('user_library.order_number', 'users.name');

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
        $items = DB::table('user_library')
            ->join('books', 'user_library.book_id', '=', 'books.id')
            ->join('users', 'user_library.user_id', '=', 'users.id')
            ->where('user_library.order_number', $orderNumber)
            ->select('books.title', 'user_library.format as format_type', 'user_library.price', 'user_library.discount', 'user_library.shipping', 'users.name as user_name', 'user_library.created_at', 'user_library.address', 'user_library.city', 'user_library.status')
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
        DB::table('user_library')->where('order_number', $orderNumber)->delete();
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
        // 1. Validamos todos los campos (¡Añadimos validación para pdf y audio!)
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string',
            'pages' => 'required|integer',
            'synopsis' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'formats' => 'required|array',
            'pdf_file' => 'nullable|mimes:pdf|max:50000', // Máx 50MB
            'audio_file' => 'nullable|mimes:mp3,wav|max:100000', // Máx 100MB
        ]);

        $cleanTitle = Str::slug($request->title, '_');
        $cleanTitle = ucfirst($cleanTitle);

        // 2. Procesar Portada (Pública)
        $imagePathForDB = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = $cleanTitle . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $imageName);
            $imagePathForDB = 'img/' . $imageName;
        }

        // 3. Procesar PDF Seguro (Privado)
        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfName = $cleanTitle . '.' . $request->file('pdf_file')->getClientOriginalExtension();
            // 🚀 TRUCO DEFINITIVO: Forzamos la ruta absoluta ignorando la configuración de los discos
            $request->file('pdf_file')->move(storage_path('app/private/pdfs'), $pdfName);
            $pdfPath = $pdfName;
        }

        // 4. Procesar Audio Seguro (Privado)
        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $audioName = $cleanTitle . '.' . $request->file('audio_file')->getClientOriginalExtension();
            // 🚀 TRUCO DEFINITIVO: Forzamos la ruta absoluta ignorando la configuración de los discos
            $request->file('audio_file')->move(storage_path('app/private/audios'), $audioName);
            $audioPath = $audioName;
        }

        // 5. Tomamos el precio base
        $basePrice = $request->formats['Tapa dura']['price'] ?? 0;

        // 6. Creamos el libro en la base de datos ¡y le añadimos las rutas de los archivos!
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'pages' => $request->pages,
            'synopsis' => $request->synopsis,
            'image_url' => $imagePathForDB,
            'price' => $basePrice,
            'is_bestseller' => false,
            // AÑADIDO: Guardamos las rutas de los archivos digitales en el libro
            'pdf_path' => $pdfPath,
            'audio_path' => $audioPath,
        ]);

        // 7. Guardamos los formatos
        foreach ($request->formats as $type => $data) {
            $book->formats()->create([
                'type' => $type,
                'price' => $data['price'],
            ]);
        }

        return redirect()->route('admin.inventory')->with('success', '¡Libro y archivos digitales añadidos con éxito al catálogo!');
    }

    public function updateBook(Request $request, $id)
    {
        $book = \App\Models\Book::findOrFail($id);

        // 1. Validamos los datos (puedes añadir más reglas si quieres)
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string',
            'synopsis' => 'nullable|string',
        ]);

        $percent = intval($request->discount_percentage);

        // 2. Actualizamos los datos principales (incluyendo category y usando synopsis)
        $book->update($request->only(['title', 'author', 'category', 'synopsis', 'image_url']));

        // 3. Lógica de descuentos (la que ya tenías)
        if ($percent > 0) {
            $book->discount_percent = "-" . $percent . "%";
            $book->old_price = $request->formats['Tapa dura']['price'] ?? 0;
        } else {
            $book->discount_percent = null;
            $book->old_price = null;
        }
        $book->save();

        // 4. Actualizamos los formatos
        foreach ($request->formats as $type => $data) {
            $originalPrice = floatval($data['price']);
            $finalPrice = $percent > 0 ? ($originalPrice * (1 - ($percent / 100))) : $originalPrice;

            $book->formats()->updateOrCreate(
                ['type' => $type],
                [
                    'price' => round($finalPrice, 2),
                ]
            );
        }

        return redirect()->route('admin.inventory')->with('success', 'Libro y género actualizados con éxito.');
    }

    public function updateStatus(\Illuminate\Http\Request $request, $orderNumber)
    {
        // Usamos update() directamente sobre la tabla library
        $affected = \Illuminate\Support\Facades\DB::table('user_library')
            ->where('order_number', $orderNumber)
            ->update(['status' => $request->input('status')]);

        if ($affected > 0) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No se encontró el pedido o el estado es el mismo']);
    }

    public function coupons()
    {
        // Traemos los cupones junto con su usuario asociado
        $coupons = \App\Models\Coupon::with('user')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.coupons', compact('coupons'));
    }

}
