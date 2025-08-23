<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BooksList;
use App\Models\RegisteredUsers;
use App\Models\BorrowBook; // ✅ Make sure you have this model

class DashboardController extends Controller
{
    public function index()
    {
        // 📚 Book stats
        $totalBooks = BooksList::count();
        $availableBooks = BooksList::where('book_status', 'Available')->count();
        $borrowedBooks = BooksList::where('book_status', 'Borrowed')->count();
        $reservedBooks = BooksList::where('book_status', 'Reserved')->count();
        $removedBooks = BooksList::where('book_status', 'Removed')->count();

        // 👥 User stats
        $facultyCount = RegisteredUsers::where('role', 'faculty')->count();
        $studentCount = RegisteredUsers::where('role', 'student')->count();

        // 📊 Borrowing stats (Approved only)
        $facultyBorrowed = BorrowBook::whereHas('user', fn($q) => $q->where('role', 'faculty'))
            ->where('status', 'Approved')
            ->count();

        $studentBorrowed = BorrowBook::whereHas('user', fn($q) => $q->where('role', 'student'))
            ->where('status', 'Approved')
            ->count();

        return view('Dashboard.dashboard', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'reservedBooks',
            'removedBooks',
            'facultyCount',
            'studentCount',
            'facultyBorrowed',   // ✅ matches Blade
            'studentBorrowed'    // ✅ matches Blade
        ));
    }

    // 📚 Fetch books by status (AJAX)
    public function getBooksData(Request $request)
    {
        $type = $request->query('type');

        $statusMap = [
            'available' => 'Available',
            'borrowed'  => 'Borrowed',
            'reserved'  => 'Reserved',
            'removed'   => 'Removed',
        ];

        $query = BooksList::query();

        if (array_key_exists($type, $statusMap)) {
            $query->where('book_status', $statusMap[$type]);
        }

        $books = $query->get(['book_id', 'book_title', 'book_author', 'book_status']);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    // 👥 Fetch users by role (AJAX)
    public function getUsersData(Request $request)
    {
        $type = $request->query('type');

        $roleMap = [
            'faculty' => 'faculty',
            'student' => 'student',
        ];

        $query = RegisteredUsers::query();

        if (array_key_exists($type, $roleMap)) {
            $query->where('role', $roleMap[$type]);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid user type']);
        }

        // Select needed fields for display
        $users = $query->get(['id', 'fullname', 'email', 'role']);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
