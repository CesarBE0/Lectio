<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('¡Bienvenido a Lectio! Confirma tu correo')
                ->greeting('¡Hola, ' . $notifiable->name . '!')
                ->line('Estamos encantados de tenerte en nuestra exclusiva librería.')
                ->line('Para empezar a comprar tus libros favoritos y disfrutar de todas las ventajas, por favor confirma tu dirección de correo pulsando el botón de abajo:')
                ->action('Confirmar mi cuenta en Lectio', $url)
                ->line('Si tú no te has registrado en Lectio, no te preocupes, puedes ignorar este mensaje.')
                ->salutation('Con cariño, el equipo de Lectio.');
        });
    }
}
