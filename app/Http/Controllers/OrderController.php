<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->books()->orderByPivot('created_at', 'desc')->get();

        return view('orders.index', compact('orders'));
    }

    public function downloadInvoice($id)
    {
        $user = Auth::user();
        $book = $user->books()->wherePivot('order_number', $id)->firstOrFail();

        $fechaCompra = $book->pivot->created_at ?? now();
        $orderNumber = $book->pivot->order_number ?? 'LCT-00000000';

        $data = [
            'user' => $user,
            'book' => $book,
            'fechaCompra' => $fechaCompra,
            'orderNumber' => $orderNumber
        ];

        $pdf = Pdf::loadView('orders.pdf', $data);

        return $pdf->download('Factura_' . $orderNumber . '.pdf');
    }
}
