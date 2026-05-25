<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Mail\WelcomeExclusiveUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeCartOnLogin;

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

        Event::listen(Verified::class, function (Verified $event) {
            Mail::to($event->user->email)->send(new WelcomeExclusiveUser($event->user));
        });

        Event::listen(Login::class, MergeCartOnLogin::class);
    }
}
