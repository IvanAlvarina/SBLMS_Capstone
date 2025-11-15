@extends('Layouts.vuexy')

@section('title', 'Add New Book')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3 text-center">Add New Book</h4>

        <form id="create-book-form" action="{{ route('books-management.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left side: form fields in grid -->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="book_title" class="form-label">Title</label>
                            <input type="text" name="book_title" class="form-control"
                                   value="{{ old('book_title', request('title')) }}" required>
                            @error('book_title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="book_author" class="form-label">Author</label>
                            <input type="text" name="book_author" class="form-control"
                                   value="{{ old('book_author', request('author')) }}" required>
                            @error('book_author') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="book_genre" class="form-label">Genre</label>
                            @include('partials.genre-dropdown', ['selectedGenre' => old('book_genre')])
                            @error('book_genre') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="book_yearpub" class="form-label">Date Published</label>
                            <input type="date" name="book_yearpub" class="form-control"
                                   value="{{ old('book_yearpub') }}"
                                   max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            @error('book_yearpub') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="book_isbn" class="form-label">ISBN</label>
                            <input type="text" name="book_isbn" id="book_isbn" class="form-control"
                                   value="{{ old('book_isbn', request('isbn')) }}" maxlength="17"
                                   pattern="(?:\d{3}-)?\d{1,5}-\d{1,7}-\d{1,7}-[\dX]{1}"
                                   title="ISBN must be either 10 or 13 digits, with optional hyphens">
                            @error('book_isbn') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="book_location" class="form-label">Location</label>
                            <select name="book_location" class="form-control" required>
                                <option value="">Select Location</option>
                                <option value="Elementary" {{ old('book_location') == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                <option value="High School" {{ old('book_location') == 'High School' ? 'selected' : '' }}>High School</option>
                                <option value="Senior High School" {{ old('book_location') == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                                <option value="College" {{ old('book_location') == 'College' ? 'selected' : '' }}>College</option>
                            </select>
                            @error('book_location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dewey_classification" class="form-label">Dewey Decimal Classification</label>
                            <select name="dewey_classification" id="dewey_classification" class="form-control">
                                <option value="">Select Dewey Classification</option>
                                <option value="000–099" {{ old('dewey_classification') == '000–099' ? 'selected' : '' }}>000–099: General Works</option>
                                <option value="100–199" {{ old('dewey_classification') == '100–199' ? 'selected' : '' }}>100–199: Philosophy and Psychology</option>
                                <option value="200–299" {{ old('dewey_classification') == '200–299' ? 'selected' : '' }}>200–299: Religion</option>
                                <option value="300–399" {{ old('dewey_classification') == '300–399' ? 'selected' : '' }}>300–399: Social Sciences</option>
                                <option value="400–499" {{ old('dewey_classification') == '400–499' ? 'selected' : '' }}>400–499: Language</option>
                                <option value="500–599" {{ old('dewey_classification') == '500–599' ? 'selected' : '' }}>500–599: Natural Sciences and Mathematics</option>
                                <option value="600–699" {{ old('dewey_classification') == '600–699' ? 'selected' : '' }}>600–699: Technology (Applied Sciences)</option>
                                <option value="700–799" {{ old('dewey_classification') == '700–799' ? 'selected' : '' }}>700–799: The Arts</option>
                                <option value="800–899" {{ old('dewey_classification') == '800–899' ? 'selected' : '' }}>800–899: Literature and Rhetoric</option>
                                <option value="900–999" {{ old('dewey_classification') == '900–999' ? 'selected' : '' }}>900–999: Geography and History</option>
                            </select>
                            @error('dewey_classification') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dewey_number" class="form-label">Dewey Number</label>
                            <input type="text" name="dewey_number" id="dewey_number" class="form-control"
                                   value="{{ old('dewey_number') }}" placeholder="e.g., 001.23" readonly>
                            @error('dewey_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cutter_sanborn" class="form-label">Cutter-Sanborn</label>
                            <input type="text" name="cutter_sanborn" id="cutter_sanborn" class="form-control"
                                   value="{{ old('cutter_sanborn') }}" placeholder="e.g., A123">
                            @error('cutter_sanborn') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <input type="hidden" name="book_status" value="Available">
                            <span class="form-control-plaintext text-success">Available</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="book_cimage" class="form-label">Book Cover Image</label>
                            <input type="file" name="book_cimage" id="book_cimage" class="form-control" accept="image/*">
                            @error('book_cimage') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Right side: cover preview -->
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                    <label class="form-label mb-2">Book Cover Preview</label>
                    <img id="cover-preview" src="#" alt="Preview"
                         style="max-width: 100%; max-height: 250px; border-radius: 6px; border: 1px solid #ddd; padding: 4px; display: none;">

                    <div id="cover-placeholder"
                         style="width: 150px; height: 200px; border: 1px solid #ddd; border-radius: 6px;
                                display: flex; align-items: center; justify-content: center;
                                font-size: 48px; color: #999; background: #f8f9fa;">
                        ?
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Add Book</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Format ISBN (ISBN-10 or ISBN-13)
function formatISBN(isbn) {
    if (!isbn) return '';
    let digits = isbn.replace(/[-\s]/g, '');
    if (digits.length === 10) return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    if (digits.length === 13) return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
    return isbn;
}

document.getElementById('book_isbn').addEventListener('input', function() {
    this.value = formatISBN(this.value);
});

// Confirm before submit
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
        if (result.isConfirmed) this.submit();
        else Swal.fire('Cancelled', 'No book was added.', 'info');
    });
});

// Live preview of uploaded image
document.getElementById('book_cimage').addEventListener('change', function(event) {
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

// Prefill fields from URL (after ISBN scan)
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const isbn = urlParams.get('isbn');
    const title = urlParams.get('title');
    const author = urlParams.get('author');

    if (isbn) document.getElementById('book_isbn').value = formatISBN(isbn);
    if (title) document.querySelector('input[name="book_title"]').value = decodeURIComponent(title);
    if (author) document.querySelector('input[name="book_author"]').value = decodeURIComponent(author);
});

// Auto-generate Dewey Number based on classification
document.getElementById('dewey_classification').addEventListener('change', function() {
    const classification = this.value;
    const numberField = document.getElementById('dewey_number');

    if (classification) {
        // Get the base number from classification (e.g., "000–099" -> "000")
        const base = classification.split('–')[0];

        // Find the next available number in this range
        fetch('/books-management/get-next-dewey-number?classification=' + encodeURIComponent(classification))
            .then(response => response.json())
            .then(data => {
                if (data.next_number) {
                    numberField.value = data.next_number;
                } else {
                    // Fallback: generate a simple incremented number
                    const randomSuffix = Math.floor(Math.random() * 100) + 1;
                    numberField.value = base + '.' + randomSuffix.toString().padStart(2, '0');
                }
            })
            .catch(error => {
                console.error('Error fetching next Dewey number:', error);
                // Fallback
                const randomSuffix = Math.floor(Math.random() * 100) + 1;
                numberField.value = base + '.' + randomSuffix.toString().padStart(2, '0');
            });
    } else {
        numberField.value = '';
    }
});

// Success notification
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    timer: 2500,
    showConfirmButton: false,
});
@endif

// Duplicate title or ISBN error notification
@if($errors->has('duplicate_title'))
Swal.fire({
    icon: 'error',
    title: 'Duplicate Title!',
    text: '{{ $errors->first('duplicate_title') }}',
});
@endif

@if($errors->has('duplicate_isbn'))
Swal.fire({
    icon: 'error',
    title: 'Duplicate ISBN!',
    text: '{{ $errors->first('duplicate_isbn') }}',
});
@endif

</script>
@endpush
