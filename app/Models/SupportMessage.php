<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = ['user_id', 'name', 'email', 'message', 'is_read', 'is_admin_reply', 'user_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
