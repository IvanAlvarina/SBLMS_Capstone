@extends('Layouts.vuexy')

@section('title', 'Add New Book')

@section('content')
<div class="card">
    <div class="card-body">
        <form id="add-book-form" action="{{ route('books-management.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="book_title" class="form-label">Title</label>
                    <input type="text" name="book_title" class="form-control" value="{{ old('book_title') }}" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="book_author" class="form-label">Author</label>
                    <input type="text" name="book_author" class="form-control" value="{{ old('book_author') }}" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="book_genre" class="form-label">Genre</label>
                    @include('partials.genre-dropdown')
                </div>

                <div class="mb-3 col-md-6">
                    <label for="book_yearpub" class="form-label">Date Published</label>
                    <input type="date" name="book_yearpub" class="form-control" value="{{ old('book_yearpub') }}" required> 
                </div>

                <div class="mb-3 col-md-6">
                    <label for="book_isbn" class="form-label">ISBN</label>
                    <input type="text" name="book_isbn" class="form-control" value="{{ old('book_isbn') }}" maxlength="13" pattern="\d{10}(\d{3})?" inputmode="numeric" title="ISBN must be exactly 13 digits" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="book_status" class="form-label">Status</label>
                    <select class="form-control" disabled>
                        <option value="Available" selected>Available</option>
                    </select>
                    <input type="hidden" name="book_status" value="Available">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="book_cimage" class="form-label">Book Cover Image (Optional)</label>
                    <input type="file" name="book_cimage" class="form-control" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
</div>
@endsection

@push('page-scripts')
<style>
.input-error {
    border: 2px solid red !important;
    box-shadow: 0 0 5px red !important;
}
.input-valid {
    border: 2px solid green !important;
    box-shadow: 0 0 5px green !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('add-book-form').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Add new book?',
        text: "Do you want to add this book?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, add it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('add-book-form');
    const inputs = form.querySelectorAll('input[required], select[required]');

    function validateInput(input) {
        if (input.value.trim() === '') {
            input.classList.add('input-error');
            input.classList.remove('input-valid');
        } else {
            input.classList.add('input-valid');
            input.classList.remove('input-error');
        }
    }

    inputs.forEach(input => {
        validateInput(input);
        input.addEventListener('input', () => validateInput(input));
        input.addEventListener('change', () => validateInput(input));
    });

    form.addEventListener('submit', function () {
        inputs.forEach(input => validateInput(input));
    });
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
