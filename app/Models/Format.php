<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Format extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'type', 'price', 'stock'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
