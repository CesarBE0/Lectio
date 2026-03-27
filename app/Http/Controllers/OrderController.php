<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Importamos la librería PDF

class OrderController extends Controller
{
    // 1. Mostrar la lista de pedidos
    public function index()
    {
        $user = Auth::user();
        // Obtenemos todos los libros comprados, ordenados por el más reciente
        $orders = $user->books()->orderByPivot('created_at', 'desc')->get();

        return view('orders.index', compact('orders'));
    }

    // 2. Generar y descargar el PDF
    public function downloadInvoice($id)
    {
        $user = Auth::user();

        // Buscamos el libro específico en la biblioteca del usuario
        $book = $user->books()->where('book_id', $id)->firstOrFail();

        $fechaCompra = $book->pivot->created_at ?? now();
        $orderNumber = $book->pivot->order_number ?? 'LCT-00000000';

        // Preparamos los datos que enviaremos a la plantilla del PDF
        $data = [
            'user' => $user,
            'book' => $book,
            'fechaCompra' => $fechaCompra,
            'orderNumber' => $orderNumber
        ];

        // Cargamos la vista del PDF, le pasamos los datos y forzamos la descarga
        $pdf = Pdf::loadView('orders.pdf', $data);

        return $pdf->download('Factura_' . $orderNumber . '.pdf');
    }
}
