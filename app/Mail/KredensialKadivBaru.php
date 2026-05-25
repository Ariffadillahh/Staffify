<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KredensialKadivBaru extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $passwordBaru;

    public function __construct($user, $passwordBaru)
    {
        $this->user = $user;
        $this->passwordBaru = $passwordBaru;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kredensial Akun Baru Kepala Divisi - Staffify BEM PNJ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kredensial_kadiv_baru',
            with: [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => $this->passwordBaru,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
