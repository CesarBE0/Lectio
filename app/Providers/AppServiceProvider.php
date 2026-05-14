<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword;

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
                ->subject('🔑 Bienvenido a Lectio - Verifica tu cuenta')
                ->view('emails.verify', [
                    'url' => $url,
                    'user' => $notifiable
                ]);
        });

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            // Laravel necesita generar la URL exacta con el token y el email del usuario
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('🔐 Restablece tu contraseña - Lectio')
                ->view('emails.reset', [
                    'url' => $url,
                    'user' => $notifiable
                ]);
        });
    }
}
