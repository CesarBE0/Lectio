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

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest(); // latest() para que salgan las más nuevas primero
    }

// Calcula la media de estrellas
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }

// Cuenta cuántas reseñas tiene
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function libraryEntries()
    {
        return $this->hasMany(\App\Models\Library::class, 'book_id');
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'book_id');
    }
}
