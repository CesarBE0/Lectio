<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'totalPrice', 'status', 'address', 'trackingNumber'];

    // Relación: Un pedido tiene muchos artículos (OrderItems)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relación: Un pedido pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
