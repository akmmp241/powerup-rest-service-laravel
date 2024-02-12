<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $details
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->details['title'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'verifyEmail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
