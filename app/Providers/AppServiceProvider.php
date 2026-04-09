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

        // PERSONALIZACIÓN DEL CORREO DE VERIFICACIÓN
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('¡Bienvenido a Lectio! Confirma tu correo') // El Asunto del correo
                ->greeting('¡Hola, ' . $notifiable->name . '!') // Saluda por su nombre
                ->line('Estamos encantados de tenerte en nuestra exclusiva librería.')
                ->line('Para empezar a comprar tus libros favoritos y disfrutar de todas las ventajas, por favor confirma tu dirección de correo pulsando el botón de abajo:')
                ->action('Confirmar mi cuenta en Lectio', $url) // El texto del botón
                ->line('Si tú no te has registrado en Lectio, no te preocupes, puedes ignorar este mensaje.')
                ->salutation('Con cariño, el equipo de Lectio.'); // La despedida
        });
    }
}
