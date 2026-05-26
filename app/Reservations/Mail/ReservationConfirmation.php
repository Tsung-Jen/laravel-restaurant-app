<?php

namespace App\Reservations\Mail;

use App\Reservations\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('messages.email_subject', ['name' => config('app.name')]),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.reservation-confirmation',
        );
    }
}
