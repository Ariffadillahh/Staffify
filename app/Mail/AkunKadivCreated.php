<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class AkunKadivCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $divisi;

    public function __construct($user, $password, $divisi)
    {
        $this->user = $user;
        $this->password = $password;
        $this->divisi = $divisi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Kepala Divisi - Staffify',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.akun_kadiv', 
        );
    }
}
