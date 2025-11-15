<?php

namespace App\Http\Controllers\BooksManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BooksList;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class BooksManagementController extends Controller
{
    // 📌 Active Books Page
    public function index()
    {
        $genres = [
            'Fiction', 'Non-Fiction', 'Science Fiction', 'Fantasy', 'Biography',
            'History', 'Mystery', 'Romance', 'Thriller', 'Self-Help', 'Children',
            'Technology', 'Other'
        ];

        return view('BooksManagement.BooksListView', compact('genres'));
    }

    // 📌 Removed Books Page
    public function removedView()
    {
        return view('BooksManagement.BooksRemovedView');
    }

    // 📌 Active Books DataTable
    public function getBooks(Request $request)
    {
        $status = $request->input('status', 'active'); 
        $genre  = $request->input('genre', '');

        $query = BooksList::query();

        if ($status === 'active') {
            $query->whereIn('book_status', ['Available', 'Borrowed', 'Reserved']);
        } elseif (in_array($status, ['Available', 'Borrowed', 'Reserved'])) {
            $query->where('book_status', $status);
        } elseif ($status === 'removed') {
            $query->where('book_status', 'Removed');
        }

        if ($genre) {
            $query->where('book_genre', $genre);
        }

        return $this->buildBooksDataTable($request, $query);
    }

    // 📌 Removed Books DataTable
    public function getRemovedBooks(Request $request)
    {
        $query = BooksList::where('book_status', 'Removed');
        return $this->buildBooksDataTable($request, $query, true);
    }

    // 📌 Shared DataTable builder
    private function buildBooksDataTable(Request $request, $query, $isRemoved = false)
    {
        $totalData = $query->count();

        // 🔍 Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('book_id', 'like', "%{$search}%")
                  ->orWhere('book_title', 'like', "%{$search}%")
                  ->orWhere('book_isbn', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        // 🔃 Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['book_id', 'book_title', 'book_author', 'book_genre', 'book_location', 'book_yearpub', 'book_isbn', 'book_status', 'book_cimage', 'book_dateadded'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // 📄 Pagination
        $books = $query->offset($request->input('start'))
                       ->limit($request->input('length'))
                       ->get();

        $data = $books->map(function ($books) use ($isRemoved) {
            return [
                'book_id'       => $books->book_id,
                'book_title'    => $books->book_title,
                'book_genre'    => $books->book_genre,
                'dewey_number'  => $books->dewey_number,
                'cutter_sanborn' => $books->cutter_sanborn,
                'book_yearpub'  => $books->book_yearpub,
                'action'        => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('books-management.edit', $books->book_id) . '">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <a class="dropdown-item text-danger delete-btn" href="javascript:void(0);" data-id="' . $books->book_id . '">
                                <i class="ti ti-trash me-1"></i> Remove
                            </a>
                        </div>
                    </div>'
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    // 📌 Create Page
    public function create()
    {
        return view('BooksManagement.BooksCreateView');
    }

    // 📌 Store Book
    public function store(Request $request)
    {
        $request->validate([
            'book_title'    => 'required|string|max:255',
            'book_author'   => 'required|string|max:255',
            'book_genre'    => 'nullable|string|max:255',
            'book_location' => 'required|string|max:255',
            'dewey_classification' => 'nullable|string|max:255',
            'dewey_number' => 'nullable|string|max:255',
            'cutter_sanborn' => 'nullable|string|max:255',
            'book_yearpub'  => 'nullable|date',
            'book_isbn'     => 'nullable|string|max:20',
            'book_status'   => 'required|in:Borrowed,Available,Reserved',
            'book_cimage'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $book = new BooksList();
            $book->book_title  = $request->book_title;
            $book->book_author = $request->book_author;
            $book->book_genre  = $request->book_genre;
            $book->book_location = $request->book_location;
            $book->dewey_classification = $request->dewey_classification;
            $book->dewey_number = $request->dewey_number;
            $book->cutter_sanborn = $request->cutter_sanborn;
            $book->book_yearpub= $request->book_yearpub;
            $book->book_isbn   = $request->book_isbn;
            $book->book_status = $request->book_status;

            if ($request->hasFile('book_cimage')) {
                $file = $request->file('book_cimage');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets'), $filename);
                $book->book_cimage = $filename;
            }

            // Custom Book ID
            $latestBook = BooksList::latest('book_id')->first();
            $nextId = $latestBook ? $latestBook->book_id + 1 : 1;
            $book->custom_book_id = 'BOOK-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $book->save();

            return redirect()->route('books-management.index')->with('success', 'Book added successfully.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $errorMessage = $e->getMessage();
                $errors = [];
                if (strpos($errorMessage, 'books_lists_book_title_unique') !== false) {
                    $errors['duplicate_title'] = 'The book title already exists.';
                }
                if (strpos($errorMessage, 'books_lists_book_isbn_unique') !== false) {
                    $errors['duplicate_isbn'] = 'The ISBN already exists.';
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
            throw $e;
        }
    }

    // 📌 Edit Book
    public function edit($id)
    {
        $book = BooksList::findOrFail($id);
        return view('BooksManagement.BooksEditView', compact('book'));
    }

    // 📌 Update Book
    public function update(Request $request, $id)
    {
        $request->validate([
            'book_title'    => 'required|string|max:255',
            'book_author'   => 'required|string|max:255',
            'book_genre'    => 'nullable|string|max:255',
            'book_location' => 'required|string|max:255',
            'book_yearpub'  => 'nullable|date_format:Y-m-d',
            'book_isbn'     => 'nullable|string|max:20',
            'book_status'   => 'required|in:Borrowed,Available,Reserved,Removed',
            'book_cimage'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dewey_classification' => 'nullable|string|max:255',
            'dewey_number' => 'nullable|string|max:255',
            'cutter_sanborn' => 'nullable|string|max:255',
        ]);

        $book = BooksList::findOrFail($id);
        $book->book_title  = $request->book_title;
        $book->book_author = $request->book_author;
        $book->book_genre  = $request->book_genre;
        $book->book_location = $request->book_location;
        $book->dewey_classification = $request->dewey_classification;
        $book->dewey_number = $request->dewey_number;
        $book->cutter_sanborn = $request->cutter_sanborn;
        $book->book_yearpub= $request->book_yearpub;
        $book->book_isbn   = $request->book_isbn;
        $book->book_status = $request->book_status;

        if ($request->hasFile('book_cimage')) {
            if ($book->book_cimage && file_exists(public_path('assets/' . $book->book_cimage))) {
                unlink(public_path('assets/' . $book->book_cimage));
            }
            $file = $request->file('book_cimage');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets'), $filename);
            $book->book_cimage = $filename;
        }

        $book->save();

        return redirect()->route('books-management.index')->with('success', 'Book updated successfully.');
    }

    // 📌 Soft Delete Book
    public function destroy($id)
    {
        $book = BooksList::findOrFail($id);
        $book->book_status = 'Removed';
        $book->save();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully.',
        ]);
    }

    // 📌 Restore Book
    public function restore($id)
    {
        $book = BooksList::findOrFail($id);

        if ($book->book_status === 'Removed') {
            $book->book_status = 'Available';
            $book->save();

            return response()->json([
                'success' => true,
                'message' => 'Book restored successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Only removed books can be restored.'
        ], 400);
    }

    // 📌 OCR View
    public function ocrCreate()
    {
        return view('BooksManagement.BooksOcrCreateView');
    }

    // 📌 ISBN Scanner View
    public function isbnScannerCreate()
    {
        return view('BooksManagement.BooksIsbnScannerCreateView');
    }

    // 📌 Get Next Dewey Number
    public function getNextDeweyNumber(Request $request)
    {
        $classification = $request->query('classification');

        if (!$classification) {
            return response()->json(['error' => 'Classification required'], 400);
        }

        // Get the base number from classification (e.g., "000–099" -> "000")
        $base = explode('–', $classification)[0];

        // Find the highest existing Dewey number in this classification range
        $existingNumbers = BooksList::where('dewey_classification', $classification)
            ->whereNotNull('dewey_number')
            ->pluck('dewey_number')
            ->toArray();

        $nextNumber = $base . '.01'; // Default starting number

        if (!empty($existingNumbers)) {
            // Extract numeric parts and find the highest
            $maxNumber = 0;
            foreach ($existingNumbers as $num) {
                // Remove base and dot, get the decimal part
                $parts = explode('.', $num);
                if (count($parts) >= 2 && $parts[0] == $base) {
                    $decimal = (int) $parts[1];
                    if ($decimal > $maxNumber) {
                        $maxNumber = $decimal;
                    }
                }
            }
            $nextDecimal = $maxNumber + 1;
            $nextNumber = $base . '.' . str_pad($nextDecimal, 2, '0', STR_PAD_LEFT);
        }

        return response()->json(['next_number' => $nextNumber]);
    }

    // 📌 Get Book Details for Modal
    public function details($id)
    {
        $book = BooksList::findOrFail($id);

        return response()->json([
            'book_id' => $book->book_id,
            'book_title' => $book->book_title,
            'book_author' => $book->book_author,
            'book_genre' => $book->book_genre,
            'book_location' => $book->book_location,
            'dewey_classification' => $book->dewey_classification,
            'dewey_number' => $book->dewey_number,
            'cutter_sanborn' => $book->cutter_sanborn,
            'book_yearpub' => $book->book_yearpub,
            'book_isbn' => $book->book_isbn,
            'book_status' => $book->book_status,
            'book_cimage' => $book->book_cimage
        ]);
    }
}
