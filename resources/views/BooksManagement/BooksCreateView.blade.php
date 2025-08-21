@extends('Layouts.vuexy')

@section('title', 'Add New Book')

@section('content')
<div class="card">
    <div class="card-body">
        <form id="create-book-form" action="{{ route('books-management.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Left side: form fields -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="book_title" class="form-label">Title</label>
                        <input type="text" name="book_title" class="form-control" value="{{ old('book_title') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="book_author" class="form-label">Author</label>
                        <input type="text" name="book_author" class="form-control" value="{{ old('book_author') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="book_genre" class="form-label">Genre</label>
                        @include('partials.genre-dropdown', ['selectedGenre' => old('book_genre')])
                    </div>

                    <div class="mb-3">
                        <label for="book_yearpub" class="form-label">Date Published</label>
                        <input type="date" name="book_yearpub" class="form-control" value="{{ old('book_yearpub') }}">
                    </div>

                    <div class="mb-3">
                        <label for="book_isbn" class="form-label">ISBN</label>
                        <input type="text" name="book_isbn" class="form-control" value="{{ old('book_isbn') }}" maxlength="13" pattern="\d{10}(\d{3})?" title="ISBN must be exactly 10 or 13 digits">
                    </div>

                    <div class="mb-3">
                        <label for="book_status" class="form-label">Status</label>
                        <select name="book_status" class="form-control" required>
                            <option value="Available" {{ old('book_status') == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Borrowed" {{ old('book_status') == 'Borrowed' ? 'selected' : '' }}>Borrowed</option>
                            <option value="Reserved" {{ old('book_status') == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="book_cimage" class="form-label">Book Cover Image (optional)</label>
                        <input type="file" name="book_cimage" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Right side: preview image -->
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-start">
                    <label class="form-label mb-3">Book Cover Preview</label>

                    <img id="cover-preview" src="#" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 6px; border: 1px solid #ddd; padding: 4px; display: none;">

                    <div id="cover-placeholder" style="width: 150px; height: 200px; border: 1px solid #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: #999; background: #f8f9fa;">
                        ?
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Add Book</button>
        </form>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('create-book-form').addEventListener('submit', function(e) {
    e.preventDefault();  
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to add this book?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, add it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        } else {
            Swal.fire('Cancelled', 'No book was added.', 'info');
        }
    });
});

// Live preview of uploaded image
document.querySelector('input[name="book_cimage"]').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('cover-preview');
    const placeholder = document.getElementById('cover-placeholder');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
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
