@extends('Layouts.vuexy')

@section('title', 'Edit Book')

@section('content')


<div class="card">
    <div class="card-body">
        <form id="edit-book-form" action="{{ route('books-management.update', $book->book_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left side: all form inputs including Change Book Cover -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="book_title" class="form-label">Title</label>
                        <input type="text" name="book_title" class="form-control" value="{{ old('book_title', $book->book_title) }}">
                    </div>

                    <div class="mb-3">
                        <label for="book_author" class="form-label">Author</label>
                        <input type="text" name="book_author" class="form-control" value="{{ old('book_author', $book->book_author) }}">
                    </div>

                    <div class="mb-3">
                        <label for="book_genre" class="form-label">Genre</label>
                        @include('partials.genre-dropdown', ['selectedGenre' => $book->book_genre])
                    </div>

                    <div class="mb-3">
                        <label for="book_yearpub" class="form-label">Date Published</label>
                        <input type="date" name="book_yearpub" class="form-control" value="{{ old('book_yearpub', \Carbon\Carbon::parse($book->book_yearpub)->format('Y-m-d')) }}">
                    </div>

                    <div class="mb-3">
                        <label for="book_isbn" class="form-label">ISBN</label>
                        <input type="text" name="book_isbn" id="book_isbn" class="form-control" value="{{ old('book_isbn', $book->book_isbn) }}" maxlength="17" pattern="(?:\d{3}-)?\d{1,5}-\d{1,7}-\d{1,7}-[\dX]{1}" title="ISBN must be either 10 or 13 digits, with optional hyphens">
                    </div>

                    <div class="mb-3">
                        <label for="book_status" class="form-label">Status</label>
                        <select name="book_status" class="form-control">
                            <option value="Available" {{ $book->book_status == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Borrowed" {{ $book->book_status == 'Borrowed' ? 'selected' : '' }}>Borrowed</option>
                            <option value="Reserved" {{ $book->book_status == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="book_cimage" class="form-label">Change Book Cover Image (optional)</label>
                        <input type="file" name="book_cimage" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Right side: current cover image preview -->
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-start">
                    <label class="form-label mb-3">Current Book Cover Image</label>

                    @if($book->book_cimage)
                        <img id="cover-preview" src="{{ asset('assets/' . $book->book_cimage) }}" alt="Book Cover" style="max-width: 100%; max-height: 300px; border-radius: 6px; border: 1px solid #ddd; padding: 4px;">
                        <div id="cover-placeholder" style="display:none;"></div>
                    @else
                        <!-- Question mark icon as placeholder -->
                        <div id="cover-placeholder" style="width: 150px; height: 200px; border: 1px solid #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: #999; background: #f8f9fa;">
                            ?
                        </div>
                        <img id="cover-preview" src="#" alt="Book Cover Preview" style="display:none; max-width: 100%; max-height: 300px; border-radius: 6px; border: 1px solid #ddd; padding: 4px;">
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Book</button>
        </form>
    </div>
</div>

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Function to format ISBN (ISBN-10 or ISBN-13)
function formatISBN(isbn) {
    if (!isbn) return ''; // Return empty string if no ISBN

    // Remove any existing hyphens or spaces
    let digits = isbn.replace(/[-\s]/g, '');

    if (digits.length === 10) {
        // Format ISBN-10: 1-234-56789-X
        return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    } else if (digits.length === 13) {
        // Format ISBN-13: 978-1-23-456789-0
        return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
    } else {
        return isbn; // Return raw if length is not 10 or 13
    }
}

// Listen for input changes on the ISBN field to format the ISBN
document.getElementById('book_isbn').addEventListener('input', function() {
    this.value = formatISBN(this.value);
});

document.getElementById('edit-book-form').addEventListener('submit', function(e) {
    e.preventDefault();  
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to save changes to this book?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        } else {
            Swal.fire('Cancelled', 'No changes were saved.', 'info');
        }
    });
});

// Live preview for Edit form cover image input
document.querySelector('input[name="book_cimage"]').addEventListener('change', function(event) {
    const fileInput = event.target;
    const preview = document.getElementById('cover-preview');
    const placeholder = document.getElementById('cover-placeholder');

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }

        reader.readAsDataURL(fileInput.files[0]);
    } else {
        // If no file selected, revert to initial display
        @if($book->book_cimage)
            preview.src = "{{ asset('assets/' . $book->book_cimage) }}";
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        @else
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
        @endif
    }
});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    timer: 2500,
    showConfirmButton: false,
});
</script>
@endif

@endpush
@endsection
