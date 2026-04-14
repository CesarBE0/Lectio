<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model {
    use HasFactory;
    protected $fillable = [
        'title', 'author', 'category', 'pages', 'image_url', 'is_bestseller',
        'synopsis', 'publisher', 'language', 'published_date', 'rating',
        'reviews_count', 'old_price', 'discount_percent'
    ];

    protected static function booted()
    {
        static::saved(function ($book) {
            Cache::flush();
        });

        static::deleted(function ($book) {
            Cache::flush();
        });
    }


    public function formats() {
        return $this->hasMany(Format::class);
    }

    public function getHardcoverPriceAttribute()
    {
        $format = $this->formats->where('type', 'Tapa dura')->first();

        return $format ? $format->price : '0.00';
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }

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
