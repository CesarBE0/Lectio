<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class WelcomeExclusiveUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // AÑADIDO: Declaramos la variable para poder usar $user->name en el correo
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope (El Asunto del correo).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido al club exclusivo de Lectio',
        );
    }

    /**
     * Get the message content definition (Aquí le decimos qué diseño usar).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome', // Esto apunta a resources/views/emails/welcome.blade.php
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
