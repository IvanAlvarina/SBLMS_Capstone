<?php

namespace App\Mail;

use App\Models\BorrowBook;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BorrowApprovedMail extends Mailable
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
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Borrow Request has been Approved')
            ->markdown('emails.borrow.approved');
    }
}
