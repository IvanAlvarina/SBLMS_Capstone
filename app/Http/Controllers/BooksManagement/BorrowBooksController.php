<?php

namespace App\Http\Controllers\BooksManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BorrowBook;
use Carbon\Carbon;
use App\Mail\BorrowApprovedMail;
use App\Mail\BorrowRejectedMail;
use Illuminate\Support\Facades\Mail;

class BorrowBooksController extends Controller
{
    /**
     * Show all pending borrow requests.
     */
    public function index()
    {   
        $borrowedBooks = BorrowBook::with(['book', 'user'])
            ->where('status', 'Pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('BooksManagement.ManageBorrowBooksView', compact('borrowedBooks'));
    }

    /**
     * Approve a borrow request.
     */
    public function approve($id)
    {
        $borrow = BorrowBook::with(['book', 'user'])->findOrFail($id);

        // Update borrow request
        $borrow->status = 'Approved';
        $borrow->approved_at = now();

        // Duration based on role (case-insensitive match)
        $role = strtolower($borrow->user->role);
        if ($role === 'student') {
            $borrow->due_date = Carbon::now()->addWeek();
        } elseif ($role === 'faculty') {
            $borrow->due_date = Carbon::now()->addMonth();
        }

        $borrow->save();

        // ✅ Mark book as Borrowed
        if ($borrow->book) {
            $borrow->book->update(['book_status' => 'Borrowed']);
        }

        // Send notification (fail silently if email error)
        try {
            Mail::to($borrow->user->email)->send(new BorrowApprovedMail($borrow));
        } catch (\Exception $e) {
            \Log::error('Mail sending failed (Approve): '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Borrow request approved and book marked as Borrowed.');
    }

    /**
     * Reject a borrow request.
     */
    public function reject($id)
    {
        $borrow = BorrowBook::with(['book', 'user'])->findOrFail($id);

        // Update request
        $borrow->status = 'Rejected';
        $borrow->save();

        // ✅ Ensure book is Available again
        if ($borrow->book) {
            $borrow->book->update(['book_status' => 'Available']);
        }

        // Send notification (fail silently if email error)
        try {
            Mail::to($borrow->user->email)->send(new BorrowRejectedMail($borrow));
        } catch (\Exception $e) {
            \Log::error('Mail sending failed (Reject): '.$e->getMessage());
        }

        return redirect()->back()->with('info', 'Borrow request rejected.');
    }
}
