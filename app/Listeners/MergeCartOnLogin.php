<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;

class MergeCartOnLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Si el usuario tiene datos de carrito guardados en la base de datos
        if (!empty($user->cart_data)) {
            // Los inyectamos en la sesión actual
            Session::put('cart', $user->cart_data);
        }
    }
}
