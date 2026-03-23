<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = \App\Models\User::first();
        $books = \App\Models\Book::all();

        $pedidos = [
            ['totalPrice' => 45.50, 'status' => 'completed'],
            ['totalPrice' => 120.00, 'status' => 'completed'],
            ['totalPrice' => 89.90, 'status' => 'completed'],
        ];

        foreach ($pedidos as $p) {
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'totalPrice' => $p['totalPrice'],
                'status' => $p['status'],
                'address' => 'Calle Principal 123, Ciudad Lectio',
                'trackingNumber' => 'LEC-' . strtoupper(\Illuminate\Support\Str::random(8)),
            ]);

            // SIMULAMOS LA VENTA DE 2 LIBROS POR PEDIDO
            for ($i = 0; $i < 2; $i++) {
                $book = $books->random();
                $formatos = ['Tapa dura', 'E-book', 'Audiolibro'];

                \DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'format_type' => $formatos[array_rand($formatos)], // Formato aleatorio
                    'price' => $p['totalPrice'] / 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

}
