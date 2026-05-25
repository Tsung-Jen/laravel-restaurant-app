<?php

namespace App\Contact\Mail;

use App\Contact\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contact $contact
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->contact->subject,
            replyTo: $this->contact->email,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.contact',
        );
    }
}
