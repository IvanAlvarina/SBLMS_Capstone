<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\BorrowBook;

class BorrowRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $borrow;

    /**
     * Create a new message instance.
     */
    public function __construct(BorrowBook $borrow)
    {
        $this->borrow = $borrow;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Borrow Request Rejected',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.borrow.rejected', // Use `view:` instead of `markdown:` since you're using HTML
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
