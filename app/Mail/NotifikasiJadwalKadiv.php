<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class NotifikasiJadwalKadiv extends Mailable
{
    use Queueable, SerializesModels;

    public $pendaftaran;
    public $jadwal;

    public function __construct($pendaftaran, $jadwal)
    {
        $this->pendaftaran = $pendaftaran;
        $this->jadwal = $jadwal;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ada Pendaftar Baru Booking Jadwal Wawancara!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notif_jadwal_kadiv',
        );
    }
}
