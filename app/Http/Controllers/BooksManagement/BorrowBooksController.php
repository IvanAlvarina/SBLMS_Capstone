<?php

namespace App\Http\Controllers\BooksManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BorrowBook;
use App\Models\BooksList;
use Carbon\Carbon;
use App\Mail\BorrowApprovedMail;
use App\Mail\BorrowRejectedMail;
use Illuminate\Support\Facades\Mail;


class BorrowBooksController extends Controller
{
    public function index()
    {   
        return view('BooksManagement.ManageBorrowBooksView');
    }

    public function getBorrowedBooks(Request $request)
    {
        $query = BorrowBook::with('book', 'user')->where('status', 'Pending');

        $totalData = BorrowBook::count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', function ($q2) use ($search) {
                    $q2->where('book_title', 'like', "%{$search}%")
                    ->orWhere('book_isbn', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q3) use ($search) {
                    $q3->where('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['id', 'book_title', 'user_name', 'role', 'status', 'created_at', 'approved_at', 'due_date'];
            if (isset($columns[$orderColIndex])) {
                $query->orderBy($columns[$orderColIndex], $orderDir);
            }
        }

        // Pagination
        $borrowedBooks = $query->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        $data = [];
        foreach ($borrowedBooks as $borrow) {
            // Role badge inline HTML
            $roleHtml = '<span class="badge '.
                ($borrow->user->role === 'Student' ? 'bg-primary' :
                ($borrow->user->role === 'Faculty' ? 'bg-success' : 'bg-secondary')).'">'.
                ($borrow->user->role ?? 'User').'</span>';

            // Dropdown actions inline HTML
            $actionHtml = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-text-secondary dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="'.route('borrow-books.approve', $borrow->id).'" method="POST" class="approve-form">
                            '.csrf_field().method_field('PUT').'
                            <button type="submit" class="dropdown-item text-success">
                                <i class="ti ti-check me-1"></i> Approve
                            </button>
                        </form>
                    </div>
                </div>
            ';

            $data[] = [
                'id'          => $borrow->id,
                'book_title'  => $borrow->book->book_title ?? 'N/A',
                'user_name'   => $borrow->user->fullname ?? 'N/A',
                'role'        => $roleHtml,
                'status'      => match ($borrow->status) {
                    'Approved' => '<span class="badge bg-success">Approved</span>',
                    'Pending'  => '<span class="badge bg-warning">Pending</span>',
                    default    => '<span class="badge bg-secondary">'.$borrow->status.'</span>',
                },
                'created_at'  => $borrow->created_at  ? '<pre>' . Carbon::parse($borrow->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') . '</pre>' : null,
                'action'      => $actionHtml,
            ];
        }

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
        ]);
    }

    public function approve($id)
    {
        $borrow = BorrowBook::with('book', 'user')->findOrFail($id);

        // Check if the book is already borrowed
        $existingBorrow = BorrowBook::where('book_id', $borrow->book_id)
            ->where('status', 'Approved')
            ->whereNull('return_due_at')
            ->first();

        if ($existingBorrow) {
            //email rejection to current user
            Mail::to($borrow->user->email)->send(
                new BorrowRejectedMail($borrow, $existingBorrow->due_date)
            );

            // Delete rejected request
            $borrow->delete();

            return redirect()->back()->with('error', 'This book is already borrowed by another user!');
        }

        // Approve current borrow
        $borrow->status = 'Approved';
        $borrow->approved_at = now();

        if ($borrow->user->role === 'Student') {
            $borrow->due_date = Carbon::now()->addWeek();
        } elseif ($borrow->user->role === 'Faculty') {
            $borrow->due_date = Carbon::now()->addMonth();
        }

        $borrow->save();

        // Send approval email
        Mail::to($borrow->user->email)->send(new BorrowApprovedMail($borrow));

        //update the book status to 'Borrowed'  
        $book = $borrow->book;
        $book->book_status = 'Borrowed';
        $book->save();

        // Reject & delete ALL other pending requests for the same book
        $otherRequests = BorrowBook::with('user')
            ->where('book_id', $borrow->book_id)
            ->where('id', '!=', $borrow->id)
            ->where('status', 'Pending')
            ->get();

        foreach ($otherRequests as $request) {
            // Send rejection email before delete
            Mail::to($request->user->email)->send(
                new BorrowRejectedMail($request, $borrow->due_date)
            );

            // Delete record
            $request->delete();
        }

        return redirect()->back()->with('success', 'Borrow request approved successfully!');
    }

    public function ApprovedBorrowIndex()
    {
        return view('BooksManagement.ApprovedBorrowedBooksView');
    }

    public function getApprovedBorrowedBooks(Request $request)
    {
        $query = BorrowBook::with('book', 'user')->where('status', 'Approved');

        $totalData = BorrowBook::count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', function ($q2) use ($search) {
                    $q2->where('book_title', 'like', "%{$search}%")
                    ->orWhere('book_isbn', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q3) use ($search) {
                    $q3->where('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['id', 'book_title', 'user_name', 'role', 'status', 'created_at', 'approved_at', 'due_date'];
            if (isset($columns[$orderColIndex])) {
                $query->orderBy($columns[$orderColIndex], $orderDir);
            }
        }

        // Pagination
        $borrowedBooks = $query->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        $data = [];
        foreach ($borrowedBooks as $borrow) {
            // Role badge inline HTML
            $roleHtml = '<span class="badge '.
                ($borrow->user->role === 'Student' ? 'bg-primary' :
                ($borrow->user->role === 'Faculty' ? 'bg-success' : 'bg-secondary')).'">'.
                ($borrow->user->role ?? 'User').'</span>';

            // Check if due date has passed or is today
            $canComplete = false;
            if ($borrow->due_date) {
                $due = Carbon::parse($borrow->due_date)->timezone('Asia/Manila');
                $now = Carbon::now('Asia/Manila');

                // if due date is today or earlier
                if ($now->greaterThanOrEqualTo($due)) {
                    $canComplete = true;
                }
            }

            // Dropdown actions inline HTML
            $actionHtml = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-text-secondary dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="'.route('borrow-books.delete-approved', $borrow->id).'" method="POST" class="approve-form">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="dropdown-item text-success" '.(!$canComplete ? 'disabled' : '').'>
                                <i class="ti ti-check me-1"></i> Complete
                            </button>
                        </form>
                    </div>
                </div>
            ';

            $daysRemaining = null;

            if ($borrow->due_date) {
                $due = Carbon::parse($borrow->due_date)->timezone('Asia/Manila');
                $now = Carbon::now('Asia/Manila');

                // Force integer (no decimals)
                $diff = (int) $now->diffInDays($due, false);

                if ($diff > 0) {
                    $daysRemaining = '<span class="badge bg-info">'.$diff.' day'.($diff > 1 ? 's' : '').' left</span>';
                } elseif ($diff === 0) {
                    $daysRemaining = '<span class="badge bg-warning">Due today</span>';
                } else {
                    $daysRemaining = '<span class="badge bg-danger">'.abs($diff).' day'.(abs($diff) > 1 ? 's' : '').' overdue</span>';
                }
            }


            $data[] = [
                'id'          => $borrow->id,
                'book_title'  => $borrow->book->book_title ?? 'N/A',
                'user_name'   => $borrow->user->fullname ?? 'N/A',
                'role'        => $roleHtml,
                'status'      => match ($borrow->status) {
                    'Approved' => '<span class="badge bg-success">Approved</span>',
                    'Pending'  => '<span class="badge bg-warning">Pending</span>',
                    default    => '<span class="badge bg-secondary">'.$borrow->status.'</span>',
                },
                'due_date'  => $borrow->due_date  ? '<pre>' . Carbon::parse($borrow->due_date)->timezone('Asia/Manila')->format('M d, Y h:i A') . '</pre>' : null,
                'days_left'   => $daysRemaining,
                'action'      => $actionHtml,
            ];
        }

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
        ]);
    }

    public function deleteApprovedBorrowedBook($id)
    {
        try {
            // Find borrow record with its related book
            $borrow = BorrowBook::with('book')->findOrFail($id);

            // Only allow deleting if status is Approved
            if ($borrow->status !== 'Approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved borrow records can be deleted.'
                ], 400);
            }

            // Update book status to Available
            if ($borrow->book) {
                $borrow->book->book_status = 'Available';
                $borrow->book->save();
            }

            // Delete the borrow record
            $borrow->delete();

            return response()->json([
                'success' => true,
                'message' => 'Approved borrow record deleted successfully. Book is now available.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete borrow record: ' . $e->getMessage()
            ], 500);
        }
    }




}
