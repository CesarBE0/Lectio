<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'preferred_language',
        'password',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'cart_data',
        'role',
        'welcome_coupon_used', // El seguro del cupón
        'points',              // Los Puntos Lectio
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'cart_data' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'library')
            ->withPivot('progress', 'is_favorite', 'format', 'address', 'city', 'price', 'discount', 'shipping', 'order_number')
            ->withTimestamps();
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
