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
                            <input type="text" name="book_title" id="book_title" class="form-control" value="{{ old('book_title', request('title')) }}" readonly required>
                            @error('book_title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="book_author" class="form-label">Author</label>
                            <input type="text" name="book_author" id="book_author" class="form-control" value="{{ old('book_author', request('author')) }}" readonly required>
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
                            <input type="date" name="book_yearpub" class="form-control" value="{{ old('book_yearpub') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            @error('book_yearpub') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="book_isbn" class="form-label">ISBN</label>
                            <input type="text" name="book_isbn" id="book_isbn" class="form-control" value="{{ old('book_isbn', request('isbn')) }}" maxlength="17" pattern="(?:\d{3}-)?\d{1,5}-\d{1,7}-\d{1,7}-[\dX]{1}" title="ISBN must be either 10 or 13 digits, with optional hyphens">
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

                <!-- Right side: scanner + cover preview -->
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                    <!-- Scanner container -->
                    <div id="scanner-container" style="position: relative; width: 100%; max-width: 300px; margin-bottom: 20px;">
                        <video id="video" style="width: 100%; border: 1px solid #ccc; border-radius: 8px;"></video>
                        <canvas id="canvas" style="position: absolute; top: 0; left: 0;"></canvas>

                        <!-- Center overlay box -->
                        <div style="
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            width: 80%;
                            height: 80px;
                            border: 3px dashed #00bfff;
                            transform: translate(-50%, -50%);
                            pointer-events: none;
                            border-radius: 8px;
                        "></div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2 w-100 mb-3">
                        <button type="button" id="upload-isbn-btn" class="btn btn-secondary">Upload ISBN Image</button>
                        <input type="file" id="isbn-upload" accept="image/*" style="display: none;">
                        <button type="button" id="manual-isbn-btn" class="btn btn-outline-primary">Enter ISBN Manually</button>
                    </div>

                    <!-- Manual ISBN Input (hidden initially) -->
                    <div id="manual-isbn-section" style="display: none; margin-bottom: 20px;">
                        <label for="manual-isbn-input" class="form-label">Enter ISBN Manually</label>
                        <input type="text" id="manual-isbn-input" class="form-control" placeholder="e.g., 978-0-123456-78-9" maxlength="17">
                        <button type="button" id="fetch-manual-isbn" class="btn btn-sm btn-primary mt-2">Fetch Book Data</button>
                    </div>

                    <!-- Cover Preview -->
                    <label class="form-label mb-2">Book Cover Preview</label>
                    <img id="cover-preview" src="#" alt="Preview" style="max-width:100%; max-height:200px; border-radius:6px; border:1px solid #ddd; padding:4px; display:none;">
                    <div id="cover-placeholder" style="width:120px; height:160px; border:1px solid #ddd; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:36px; color:#999; background:#f8f9fa;">?</div>

                    <p class="mt-2">Latest Scanned ISBN: <strong id="isbn-result">None</strong></p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
// ===== ISBN Formatter =====
function formatISBN(isbn) {
    if (!isbn) return '';
    let digits = isbn.replace(/[-\s]/g, '');
    if (digits.length === 10) return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    if (digits.length === 13) return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
    return isbn;
}

// ===== Form Confirm =====
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
    }).then((result) => { if (result.isConfirmed) this.submit(); else Swal.fire('Cancelled', 'No book was added.', 'info'); });
});

// ===== Cover Preview =====
function updateCoverPreview(src) {
    const preview = document.getElementById('cover-preview');
    const placeholder = document.getElementById('cover-placeholder');
    if (src) {
        preview.src = src;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
    } else {
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
    }
}

// ===== Fetch Book Data =====
function fetchBookData(isbn) {
    fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&format=json&jscmd=data`)
        .then(response => response.json())
        .then(data => {
            const bookKey = `ISBN:${isbn}`;
            if (data[bookKey]) {
                const book = data[bookKey];
                document.getElementById('book_title').value = book.title || '';
                document.getElementById('book_author').value = (book.authors && book.authors.map(a => a.name).join(', ')) || '';
                document.getElementById('book_isbn').value = formatISBN(isbn);

                // Display cover if available
                if (book.cover && book.cover.large) {
                    updateCoverPreview(book.cover.large);
                } else if (book.cover && book.cover.medium) {
                    updateCoverPreview(book.cover.medium);
                } else if (book.cover && book.cover.small) {
                    updateCoverPreview(book.cover.small);
                } else {
                    updateCoverPreview(null);
                }
            } else {
                Swal.fire('No Data', 'Book data not found for this ISBN.', 'info');
            }
        })
        .catch(err => {
            console.error('Error fetching book data:', err);
            Swal.fire('Error', 'Failed to fetch book data.', 'error');
        });
}

// ===== Camera Scanner =====
document.addEventListener('DOMContentLoaded', function () {
    const isbnResult = document.getElementById('isbn-result');
    const videoEl = document.getElementById('video');
    const canvasEl = document.getElementById('canvas');
    const canvasCtx = canvasEl.getContext('2d');

    function startScanner() {
        if (!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)) {
            alert('Camera not supported on this device/browser.');
            return;
        }

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: videoEl,
                constraints: {
                    facingMode: "environment",
                    width: { min: 320, ideal: 640, max: 1280 },
                    height: { min: 240, ideal: 480, max: 720 }
                }
            },
            decoder: { readers: ["ean_reader"] }, // ISBN-13
            locate: true
        }, function(err) {
            if (err) {
                console.error(err);
                alert('Camera initialization failed.');
                return;
            }
            Quagga.start();
        });

        Quagga.onProcessed(function(result) {
            if (!videoEl.videoWidth) return;

            canvasEl.width = videoEl.videoWidth;
            canvasEl.height = videoEl.videoHeight;
            canvasCtx.clearRect(0, 0, canvasEl.width, canvasEl.height);

            if (result && result.boxes) {
                result.boxes
                    .filter(box => box !== result.box)
                    .forEach(box => drawPath(box, 'rgba(255, 255, 255, 0.3)'));
            }
            if (result && result.box) drawPath(result.box, 'rgba(0, 191, 255, 0.6)');
        });

        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            if (code.length === 13 || code.length === 10) {
                isbnResult.textContent = code;
                fetchBookData(code);
            }
        });
    }

    function drawPath(path, color) {
        canvasCtx.strokeStyle = color;
        canvasCtx.lineWidth = 3;
        canvasCtx.beginPath();
        for (let i = 0; i < path.length; i++) {
            const point = path[i];
            if (i === 0) canvasCtx.moveTo(point.x, point.y);
            else canvasCtx.lineTo(point.x, point.y);
        }
        canvasCtx.closePath();
        canvasCtx.stroke();
    }

    setTimeout(startScanner, 500);
});

// ===== Upload ISBN Image =====
document.getElementById('upload-isbn-btn').addEventListener('click', () => {
    document.getElementById('isbn-upload').click();
});

document.getElementById('isbn-upload').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    // Check file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        Swal.fire({
            icon: 'error',
            title: 'File Too Large',
            text: 'Please select an image smaller than 5MB.'
        });
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        const img = new Image();
        img.onload = () => {
            // Preprocess image for better detection
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            // Convert to grayscale and increase contrast
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            for (let i = 0; i < data.length; i += 4) {
                const gray = 0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2];
                const contrast = 1.5;
                const factor = (259 * (contrast + 255)) / (255 * (259 - contrast));
                const contrasted = factor * (gray - 128) + 128;
                const val = Math.min(255, Math.max(0, contrasted));
                data[i] = data[i + 1] = data[i + 2] = val;
            }
            ctx.putImageData(imageData, 0, 0);

            // Scan barcode from processed image
            Quagga.decodeSingle({
                src: canvas.toDataURL(),
                numOfWorkers: 0,
                inputStream: {
                    size: 800
                },
                decoder: {
                    readers: ["ean_reader", "code_128_reader"] // Support both ISBN-13 and ISBN-10
                }
            }, function(result) {
                if (result && result.codeResult) {
                    const code = result.codeResult.code;
                    if (code.length === 13 || code.length === 10) {
                        document.getElementById('isbn-result').textContent = code;
                        fetchBookData(code);
                    } else {
                        Swal.fire('Invalid ISBN', 'No valid ISBN barcode found in the image.', 'warning');
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Barcode Detected',
                        text: 'Try uploading a clearer image or enter ISBN manually.',
                        showCancelButton: true,
                        confirmButtonText: 'Enter Manually',
                        cancelButtonText: 'Try Again'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('manual-isbn-btn').click();
                        }
                    });
                }
            });
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});

// ===== Manual ISBN Toggle =====
document.getElementById('manual-isbn-btn').addEventListener('click', function() {
    const section = document.getElementById('manual-isbn-section');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
});

// ===== Fetch Manual ISBN =====
document.getElementById('fetch-manual-isbn').addEventListener('click', function() {
    const isbnInput = document.getElementById('manual-isbn-input');
    const isbn = isbnInput.value.trim().replace(/[-\s]/g, '');

    if (!isbn || (isbn.length !== 10 && isbn.length !== 13)) {
        Swal.fire('Invalid ISBN', 'Please enter a valid 10 or 13-digit ISBN.', 'warning');
        return;
    }

    document.getElementById('isbn-result').textContent = isbnInput.value.trim();
    fetchBookData(isbn);
});

// ===== Auto-generate Dewey Number =====
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

// ===== Notifications =====
@if(session('success'))
Swal.fire({ icon:'success', title:'Success!', text:'{{ session('success') }}', timer:2500, showConfirmButton:false });
@endif
@if($errors->has('duplicate_title'))
Swal.fire({ icon:'error', title:'Duplicate Title!', text:'{{ $errors->first('duplicate_title') }}' });
@endif
@if($errors->has('duplicate_isbn'))
Swal.fire({ icon:'error', title:'Duplicate ISBN!', text:'{{ $errors->first('duplicate_isbn') }}' });
@endif

</script>
@endpush
