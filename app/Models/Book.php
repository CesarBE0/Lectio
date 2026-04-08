<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache; // 👈 Añadimos Cache

class Book extends Model {
    protected $fillable = [
        'title', 'author', 'category', 'pages', 'image_url', 'is_bestseller',
        'synopsis', 'publisher', 'language', 'published_date', 'rating',
        'reviews_count', 'old_price', 'discount_percent'
    ];

    // 👇 MAGIA DE INVALIDACIÓN DE CACHÉ 👇
    protected static function booted()
    {
        // Cuando guardes un libro (crear o editar) desde tu panel admin...
        static::saved(function ($book) {
            Cache::flush(); // Borramos toda la memoria caché para forzar que coja los datos nuevos
        });

        // Cuando elimines un libro...
        static::deleted(function ($book) {
            Cache::flush();
        });
    }
    // -------------------------------------

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
