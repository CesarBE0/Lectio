<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id'];

    // Relación: Una lista de deseos pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un registro de lista de deseos pertenece a un libro
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
