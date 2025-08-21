<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BooksList;

class DashboardController extends Controller
{
    public function index()
{
    $totalBooks = BooksList::count();
    $availableBooks = BooksList::where('book_status', 'Available')->count();
    $borrowedBooks = BooksList::where('book_status', 'Borrowed')->count();
    $reservedBooks = BooksList::where('book_status', 'Reserved')->count();
    $removedBooks = BooksList::where('book_status', 'Removed')->count();

    return view('Dashboard.dashboard', compact('totalBooks', 'availableBooks', 'borrowedBooks', 'reservedBooks', 'removedBooks'));
}

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
        'books' => $books
    ]);
}

    
}
