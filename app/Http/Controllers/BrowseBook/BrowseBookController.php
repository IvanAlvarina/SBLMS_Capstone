<?php

namespace App\Http\Controllers\BrowseBook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BooksList;
use App\Models\BorrowBook;
use Illuminate\Support\Facades\Auth;

class BrowseBookController extends Controller
{
    public function index(Request $request)
    {
        $query = BooksList::where('book_status', 'Available');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('book_title', 'LIKE', "%$search%")
                ->orWhere('book_author', 'LIKE', "%$search%")
                ->orWhere('book_genre', 'LIKE', "%$search%");
            });
        }

        $books = $query->paginate(12);

        // Get current user's pending/approved borrow requests
        $userBorrows = $this->getUserActiveBorrows(Auth::id());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('BrowseBook.BrowseBookView', compact('books', 'userBorrows'))->render()
            ]);
        }

        return view('BrowseBook.BrowseBookView', compact('books', 'userBorrows'));
    }


    public function viewDetails($id)
    {
        $book = BooksList::findOrFail($id);

        $userBorrows = $this->getUserActiveBorrows(Auth::id());

        return view('BrowseBook.BookDetailsView', compact('book', 'userBorrows'));
    }

    private function getUserActiveBorrows($userId)
    {
        return BorrowBook::where('user_id', $userId)
            ->whereIn('status', ['Pending', 'Approved'])
            ->pluck('book_id')
            ->toArray();
    }

    public function borrow($id)
    {
        $book = BooksList::findOrFail($id);

        $activeBorrows = BorrowBook::where('user_id', Auth::id())
            ->whereIn('status', ['Pending', 'Approved'])
            ->count();

        if (Auth::user()->role === 'Faculty') {
            // Faculty: max 4 books
            if ($activeBorrows >= 4) {
                return redirect()->back()->with('error', 'As a Faculty member, you can only borrow up to 4 books at a time.');
            }
        } else {
            // Students/others: max 3 books
            if ($activeBorrows >= 3) {
                return redirect()->back()->with('error', 'You can only borrow up to 3 books at a time.');
            }
        }

        $alreadyRequested = BorrowBook::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        if ($alreadyRequested) {
            return redirect()->back()->with('error', 'You have already requested this book.');
        }

        BorrowBook::create([
            'book_id' => $book->book_id,
            'user_id' => Auth::id(),
            'status' => 'Pending',
        ]);

        return redirect()->route('browsebook.index')->with('success', 'Borrow request submitted. Please wait for admin approval.');
    }

    public function myBorrows()
    {
        $borrows = BorrowBook::with('book')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('BrowseBook.MyBorrowsView', compact('borrows'));
    }


}
