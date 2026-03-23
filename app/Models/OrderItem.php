<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'book_id', 'format_type', 'price', 'quantity'];

    // Relación: Un detalle de pedido pertenece a un Libro
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
