<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\UserManagement\UserManagementController;
use App\Http\Controllers\BooksManagement\BooksManagementController;
use App\Http\Controllers\Chatbot\ChatbotController;
use App\Http\Controllers\BrowseBook\BrowseBookController;
use App\Http\Controllers\BooksManagement\BorrowBooksController;
use App\Http\Controllers\Ejournals\EjournalsController;
use App\Http\Controllers\Ebooks\EbooksController;
use App\Http\Controllers\NewsAndMagazine\NewsAndMagazineController;
use App\Http\Controllers\OER\OERController;


//  Root route - redirect to login
Route::get('/', function () {
    return redirect()->route('login.index');
});

//  Login routes (with guest middleware to prevent authenticated users)
Route::middleware('guest')->group(function () {
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', [LoginController::class, 'index'])->name('login.index');
        Route::get('/register', [LoginController::class, 'register'])->name('login.register');
        Route::post('/store', [LoginController::class, 'store'])->name('login.store');
        Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
    });
});

//  Logout route (for authenticated users only)
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

//  Dashboard & other authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/books-data', [DashboardController::class, 'getBooksData']);
        Route::get('/users-data', [DashboardController::class, 'getUsersData']);
        // Add more dashboard routes here
    });

    // User Management
    Route::group(['prefix' => 'user-management'], function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('user-management.index');
        Route::get('/json', [UserManagementController::class, 'getUsers'])->name('user-management.json');
        Route::get('/json-approval', [UserManagementController::class, 'getUsersForApproval'])->name('user-management.json.approval');
        Route::get('/pending-approval', [UserManagementController::class, 'forApprovalIndex'])->name('user-management.pending-approval');
        Route::get('/users/approve/{id}', [UserManagementController::class, 'approveUser'])->name('user-management.approve');
        Route::get('/faculty-member-creation', [UserManagementController::class, 'facultyMembersCreationIndex'])->name('user-management.faculty-creation.index');
        Route::post('/faculty-member-creation/store', [UserManagementController::class, 'storeFacultyMember'])->name('user-management.faculty-creation.store');
        Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
        Route::put('/{id}/update', [UserManagementController::class, 'update'])->name('user-management.update');
        Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });


    // Books Management
     Route::group(['prefix' => 'books-management'], function () {
        Route::get('/', [BooksManagementController::class, 'index'])->name('books-management.index');
        Route::get('/json', [BooksManagementController::class, 'getBooks'])->name('books-management.json');
        Route::get('/create', [BooksManagementController::class, 'create'])->name('books-management.create');
        Route::post('/', [BooksManagementController::class, 'store'])->name('books-management.store');
        Route::get('/{book_id}/edit', [BooksManagementController::class, 'edit'])->name('books-management.edit');
        Route::put('/{book_id}', [BooksManagementController::class, 'update'])->name('books-management.update');
        Route::delete('/{book_id}', [BooksManagementController::class, 'destroy'])->name('books-management.destroy');
        Route::patch('/{book_id}/restore', [BooksManagementController::class, 'restore'])->name('books-management.restore');
        Route::get('/{book_id}/details', [BooksManagementController::class, 'details'])->name('books-management.details');
        Route::get('/get-next-dewey-number', [BooksManagementController::class, 'getNextDeweyNumber'])->name('books-management.get-next-dewey-number');

        // Removed Books
        Route::get('/removed', [BooksManagementController::class, 'removedView'])->name('books-management.removed');
        Route::get('/json-removed', [BooksManagementController::class, 'getRemovedBooks'])->name('books-management.json.removed');
    Route::get('/books-management/ocr', [BooksManagementController::class, 'ocrCreate'])->name('books-management.ocr');
    Route::get('/books-management/isbnscanner', [BooksManagementController::class, 'isbnScannerCreate'])->name('books-management.isbnscanner');


  });
  

    //  Force password change route (only for logged in Student/Faculty)
    Route::post('/force-change-password', [LoginController::class, 'forceChangePassword'])
        ->name('password.forceChange');

    Route::group(['prefix' => 'chatbot'], function () {
        Route::get('/chat/start', [ChatbotController::class, 'start'])->name('chatbot.start');
        Route::post('/chat/next', [ChatbotController::class, 'next'])->name('chatbot.next');
    });

    //browse book
    Route::group(['prefix' => 'browsebook'], function () {
        Route::get('/browse-book', [BrowseBookController::class, 'index'])->name('browsebook.index');
        Route::get('/book/{id}', [BrowseBookController::class, 'viewDetails'])->name('browsebook.show');
        Route::post('/book/{id}/borrow', [BrowseBookController::class, 'borrow'])->name('browsebook.borrow');
        Route::get('/my-borrows', [BrowseBookController::class, 'myBorrows'])->name('browsebook.myborrows');
    });

    Route::group(['prefix' => 'borrow-books'], function () {
        Route::get('/borrow-books-pending', [BorrowBooksController::class, 'index'])->name('borrow-books.index');
        Route::get('/json', [BorrowBooksController::class, 'getBorrowedBooks'])->name('borrow-books.json');
        Route::put('/{id}/approve', [BorrowBooksController::class, 'approve'])->name('borrow-books.approve');
        Route::get('/borrow-books-approved', [BorrowBooksController::class, 'ApprovedBorrowIndex'])->name('borrow-books.approved');
        Route::get('/json-approved', [BorrowBooksController::class, 'getApprovedBorrowedBooks'])->name('borrow-books.json.approved');
        Route::delete('/borrow-books/{id}/delete-approved', [BorrowBooksController::class, 'deleteApprovedBorrowedBook'])->name('borrow-books.delete-approved');
    });

    Route::group(['prefix' => 'ejournals'], function () {
        Route::get('/', [EjournalsController::class, 'index'])->name('ejournals.index');
        Route::get('/e-journal-list', [EjournalsController::class, 'list'])->name('ejournals.list');
        Route::get('/get-data', [EjournalsController::class, 'getJournalData'])->name('ejournals.getData');
        Route::get('/add-ejournal', [EjournalsController::class, 'addEjournal'])->name('ejournals.add');
        Route::post('/store', [EjournalsController::class, 'store'])->name('ejournals.store');
        Route::get('/{id}/edit', [EjournalsController::class, 'edit'])->name('ejournals.edit');
        Route::put('/{id}', [EjournalsController::class, 'update'])->name('ejournals.update');
        Route::delete('/{id}', [EjournalsController::class, 'destroy'])->name('ejournals.destroy');
    });

    Route::group(['prefix' => 'ebooks'], function () {
        Route::get('/', [EbooksController::class, 'index'])->name('ebooks.index');
        Route::get('/e-book-list', [EbooksController::class, 'list'])->name('ebooks.list');
        Route::get('/get-data', [EbooksController::class, 'getEbookData'])->name('ebooks.getData');
        Route::get('/add-ebook', [EbooksController::class, 'addEbook'])->name('ebooks.add');
        Route::post('/store', [EbooksController::class, 'store'])->name('ebooks.store');
        Route::get('/{id}/edit', [EbooksController::class, 'edit'])->name('ebooks.edit');
        Route::put('/{id}', [EbooksController::class, 'update'])->name('ebooks.update');
        Route::delete('/{id}', [EbooksController::class, 'destroy'])->name('ebooks.destroy');
    });

    Route::group(['prefix' => 'news&magazine'], function () {
        Route::get('/', [NewsAndMagazineController::class, 'index'])->name('news&magazine.index');
        Route::get('/news-magazine-list', [NewsAndMagazineController::class, 'list'])->name('news&magazine.list');
        Route::get('/get-data', [NewsAndMagazineController::class, 'getNewsData'])->name('news&magazine.getData');
        Route::get('/add-news-magazine', [NewsAndMagazineController::class, 'addNews'])->name('news&magazine.add');
        Route::post('/store', [NewsAndMagazineController::class, 'store'])->name('news&magazine.store');
        Route::get('/{id}/edit', [NewsAndMagazineController::class, 'edit'])->name('news&magazine.edit');
        Route::put('/{id}', [NewsAndMagazineController::class, 'update'])->name('news&magazine.update');
        Route::delete('/{id}', [NewsAndMagazineController::class, 'destroy'])->name('news&magazine.destroy');
    });

    Route::group(['prefix' => 'OER'], function () {
        Route::get('/', [OERController::class, 'index'])->name('oer.index');
        Route::get('/oer-list', [OERController::class, 'list'])->name('oer.list');
        Route::get('/get-data', [OERController::class, 'getOerData'])->name('oer.getData');
        Route::get('/add-oer', [OERController::class, 'addOer'])->name('oer.add');
        Route::post('/store', [OERController::class, 'store'])->name('oer.store');
        Route::get('/{id}/edit', [OERController::class, 'edit'])->name('oer.edit');
        Route::put('/{id}', [OERController::class, 'update'])->name('oer.update');
        Route::delete('/{id}', [OERController::class, 'destroy'])->name('oer.destroy');
    });
});