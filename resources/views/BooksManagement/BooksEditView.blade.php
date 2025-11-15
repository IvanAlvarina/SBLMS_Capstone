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
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="book_title" class="form-label">Title</label>
                            <input type="text" name="book_title" class="form-control" value="{{ old('book_title', $book->book_title) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="book_author" class="form-label">Author</label>
                            <input type="text" name="book_author" class="form-control" value="{{ old('book_author', $book->book_author) }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="book_genre" class="form-label">Genre</label>
                            @include('partials.genre-dropdown', ['selectedGenre' => $book->book_genre])
                        </div>
                        <div class="col-md-6">
                            <label for="book_yearpub" class="form-label">Date Published</label>
                            <input type="date" name="book_yearpub" class="form-control"
                                   value="{{ old('book_yearpub', \Carbon\Carbon::parse($book->book_yearpub)->format('Y-m-d')) }}"
                                   max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="book_isbn" class="form-label">ISBN</label>
                            <input type="text" name="book_isbn" id="book_isbn" class="form-control"
                                   value="{{ old('book_isbn', $book->book_isbn) }}" maxlength="17"
                                   pattern="(?:\d{3}-)?\d{1,5}-\d{1,7}-\d{1,7}-[\dX]{1}"
                                   title="ISBN must be either 10 or 13 digits, with optional hyphens" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="book_location" class="form-label">Location</label>
                            <select name="book_location" class="form-control" required>
                                <option value="">Select Location</option>
                                <option value="Elementary" {{ old('book_location', $book->book_location) == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                <option value="High School" {{ old('book_location', $book->book_location) == 'High School' ? 'selected' : '' }}>High School</option>
                                <option value="Senior High School" {{ old('book_location', $book->book_location) == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                                <option value="College" {{ old('book_location', $book->book_location) == 'College' ? 'selected' : '' }}>College</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="dewey_classification" class="form-label">Dewey Decimal Classification</label>
                            <select name="dewey_classification" id="dewey_classification" class="form-control">
                                <option value="">Select Dewey Classification</option>
                                <option value="000–099" {{ old('dewey_classification', $book->dewey_classification) == '000–099' ? 'selected' : '' }}>000–099: General Works</option>
                                <option value="100–199" {{ old('dewey_classification', $book->dewey_classification) == '100–199' ? 'selected' : '' }}>100–199: Philosophy and Psychology</option>
                                <option value="200–299" {{ old('dewey_classification', $book->dewey_classification) == '200–299' ? 'selected' : '' }}>200–299: Religion</option>
                                <option value="300–399" {{ old('dewey_classification', $book->dewey_classification) == '300–399' ? 'selected' : '' }}>300–399: Social Sciences</option>
                                <option value="400–499" {{ old('dewey_classification', $book->dewey_classification) == '400–499' ? 'selected' : '' }}>400–499: Language</option>
                                <option value="500–599" {{ old('dewey_classification', $book->dewey_classification) == '500–599' ? 'selected' : '' }}>500–599: Natural Sciences and Mathematics</option>
                                <option value="600–699" {{ old('dewey_classification', $book->dewey_classification) == '600–699' ? 'selected' : '' }}>600–699: Technology (Applied Sciences)</option>
                                <option value="700–799" {{ old('dewey_classification', $book->dewey_classification) == '700–799' ? 'selected' : '' }}>700–799: The Arts</option>
                                <option value="800–899" {{ old('dewey_classification', $book->dewey_classification) == '800–899' ? 'selected' : '' }}>800–899: Literature and Rhetoric</option>
                                <option value="900–999" {{ old('dewey_classification', $book->dewey_classification) == '900–999' ? 'selected' : '' }}>900–999: Geography and History</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="dewey_number" class="form-label">Dewey Number</label>
                            <input type="text" name="dewey_number" id="dewey_number" class="form-control"
                                   value="{{ old('dewey_number', $book->dewey_number) }}" placeholder="e.g., 001.23" readonly>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="cutter_sanborn" class="form-label">Cutter-Sanborn</label>
                            <input type="text" name="cutter_sanborn" id="cutter_sanborn" class="form-control"
                                   value="{{ old('cutter_sanborn', $book->cutter_sanborn) }}" placeholder="e.g., A123">
                        </div>
                        <div class="col-md-6">
                            <label for="book_status" class="form-label">Status</label>
                            <select name="book_status" class="form-control">
                                <option value="Available" {{ old('book_status', $book->book_status) == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Borrowed" {{ old('book_status', $book->book_status) == 'Borrowed' ? 'selected' : '' }}>Borrowed</option>
                                <option value="Reserved" {{ old('book_status', $book->book_status) == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                                <option value="Removed" {{ old('book_status', $book->book_status) == 'Removed' ? 'selected' : '' }}>Removed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
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
function formatISBN(isbn) {
    if (!isbn) return '';
    let digits = isbn.replace(/[-\s]/g, '');
    if (digits.length === 10) return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    else if (digits.length === 13) return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
    else return isbn;
}

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
