<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    // 1. Le decimos a Laravel que la tabla se llama 'library' y no 'libraries'
    protected $table = 'library';

    // 2. Permitimos que se guarden datos en estos campos
    protected $fillable = [
        'user_id', 'book_id', 'format', 'quantity', 'address', 'city',
        'price', 'discount', 'shipping', 'order_number',
        'status', 'trackingNumber', 'progress', 'is_favorite'
    ];

    // 3. Relación: Una entrada de la biblioteca pertenece a un libro
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // 4. Relación: Una entrada de la biblioteca pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
