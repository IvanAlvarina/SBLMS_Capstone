<?php

namespace App\Http\Controllers\BooksManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BorrowBook;
use Carbon\Carbon;
use App\Mail\BorrowApprovedMail;
use Illuminate\Support\Facades\Mail;


class BorrowBooksController extends Controller
{
    public function index()
    {   
        $borrowedBooks = BorrowBook::with('book', 'user')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('BooksManagement.ManageBorrowBooksView', compact('borrowedBooks'));
    }

    public function approve($id)
    {
        $borrow = BorrowBook::with('book', 'user')->findOrFail($id);

        // Mark as approved
        $borrow->status = 'Approved';
        $borrow->approved_at = now();

        // Duration based on role
        if ($borrow->user->role === 'Student') {
            $borrow->due_date = Carbon::now()->addWeek();
        } elseif ($borrow->user->role === 'Faculty') {
            $borrow->due_date = Carbon::now()->addMonth();
        }

        $borrow->save();

        // Send email notification
        Mail::to($borrow->user->email)->send(new BorrowApprovedMail($borrow));

        return redirect()->back()->with('success', 'Borrow request approved successfully!');
    }
}
