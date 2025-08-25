<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BorrowRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

   public $borrow;
    public $dueDate;

    public function __construct($borrow, $dueDate)
    {
        $this->borrow = $borrow;
        $this->dueDate = $dueDate;
    }

    public function build()
    {
        return $this->subject('Book Borrow Request - Unavailable')
            ->view('emails.borrow.borrow_rejected')
            ->with([
                'bookTitle' => $this->borrow->book->book_title,
                'dueDate'   => $this->dueDate,
            ]);
    }

}
