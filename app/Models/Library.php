<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $table = 'library';

    protected $fillable = [
        'user_id', 'book_id', 'format', 'quantity', 'address', 'city',
        'price', 'discount', 'shipping', 'order_number',
        'status', 'trackingNumber', 'progress', 'is_favorite'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
