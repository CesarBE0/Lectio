<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    protected $fillable = [
        'title', 'author', 'category', 'pages', 'image_url', 'is_bestseller',
        'synopsis', 'publisher', 'language', 'published_date', 'rating',
        'reviews_count', 'old_price', 'discount_percent'
    ];

    public function formats() {
        return $this->hasMany(Format::class);
    }

    // Acceso directo al precio de Tapa Dura para la Home
    public function getHardcoverPriceAttribute()
    {
        // Buscamos el formato que se llame exactamente 'Tapa dura'
        $format = $this->formats->where('type', 'Tapa dura')->first();

        return $format ? $format->price : '0.00';
    }
}
