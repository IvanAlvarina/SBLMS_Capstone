@extends('Layouts.vuexy')

@section('title', 'Add New Book')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3 text-center">Add New Book</h4>

        <!-- Add Book Form -->
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
                            <select name="dewey_classification" class="form-control">
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
                            <label class="form-label">Status</label>
                            <input type="hidden" name="book_status" value="Available">
                            <span class="form-control-plaintext text-success">Available</span>
                        </div>
                    </div>
                    <input type="file" name="book_cimage" id="book_cimage" class="form-control" accept="image/*" style="display:none;">
                    @error('book_cimage') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Right side: cover preview + OCR buttons -->
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                    <label class="form-label mb-2">Book Cover Preview</label>
                    <img id="cover-preview" src="#" alt="Preview" style="max-width:100%; max-height:250px; border-radius:6px; border:1px solid #ddd; padding:4px; display:none;">
                    <div id="cover-placeholder" style="width:150px; height:200px; border:1px solid #ddd; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:48px; color:#999; background:#f8f9fa;">?</div>

                    <!-- OCR Buttons -->
                    <div class="d-grid gap-2 mt-3 w-100">
                        <button type="button" id="camera-btn" class="btn btn-primary">Scan using Camera</button>
                        <button type="button" id="upload-btn" class="btn btn-secondary">Scan using Uploaded Image</button>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Add Book</button>
            </div>
        </form>
    </div>
</div>

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Camera OCR Scan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center position-relative">
        <video id="camera-video" autoplay style="width:100%; max-height:300px;"></video>
        <canvas id="camera-canvas" style="display:none;"></canvas>
        <p>Detected Text: <span id="ocr-result">None</span></p>


      </div>
      <div class="modal-footer">
        <button type="button" id="capture-btn" class="btn btn-primary">Capture & Scan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.0.2/dist/tesseract.min.js"></script>

<script>
// ===== ISBN Formatter =====
function formatISBN(isbn) {
    if (!isbn) return '';
    let digits = isbn.replace(/[-\s]/g, '');
    if (digits.length === 10) return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    if (digits.length === 13) return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
    return isbn;
}
document.getElementById('book_isbn').addEventListener('input', function() { this.value = formatISBN(this.value); });

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
function updateCoverPreview(file) {
    const preview = document.getElementById('cover-preview');
    const placeholder = document.getElementById('cover-placeholder');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; placeholder.style.display = 'none'; };
        reader.readAsDataURL(file);
    } else { preview.style.display = 'none'; placeholder.style.display = 'flex'; }
}

// ===== Image Preprocessing =====
function preprocessImage(image) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = image.width || image.videoWidth || image.naturalWidth;
    canvas.height = image.height || image.videoHeight || image.naturalHeight;
    ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

    const imgData = ctx.getImageData(0,0,canvas.width,canvas.height);
    for (let i=0;i<imgData.data.length;i+=4){
        const r = imgData.data[i];
        const g = imgData.data[i+1];
        const b = imgData.data[i+2];
        // Convert to grayscale
        const gray = 0.299 * r + 0.587 * g + 0.114 * b;
        // Increase contrast
        const contrast = 1.5; // Adjust as needed
        const factor = (259 * (contrast + 255)) / (255 * (259 - contrast));
        const contrasted = factor * (gray - 128) + 128;
        const val = Math.min(255, Math.max(0, contrasted));
        imgData.data[i] = imgData.data[i+1] = imgData.data[i+2] = val;
    }
    ctx.putImageData(imgData,0,0);
    return canvas;
}

// ===== Run OCR with Loading & Heuristic =====
function runOCR(imageSource) {
    Swal.fire({
        title: 'Scanning...',
        text: 'Please wait while we process the image.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    console.log('Starting OCR...');

    return Tesseract.recognize(preprocessImage(imageSource), 'eng', {
        logger: m => console.log(m),
        tessedit_pageseg_mode: Tesseract.PSM.UNIFORM_BLOCK_OF_TEXT, // PSM 6
        tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 .,-:;!?\'\"()[]{}' // Whitelist common characters
    })
        .then(({ data: { text } }) => {
            console.log('OCR completed. Detected text:', text);
            Swal.close();
            const detected = text.trim();
            document.getElementById('ocr-result').innerText = detected || "No text detected";

            // Split lines
            const lines = detected.split('\n').map(l => l.trim()).filter(Boolean);

            // Heuristic for title vs author
            if(lines.length === 1){
                document.getElementById('book_title').value = lines[0];
            } else if(lines.length >= 2){
                const sorted = [...lines].sort((a,b) => b.length - a.length); // longest line = title
                document.getElementById('book_title').value = sorted[0];
                document.getElementById('book_author').value = sorted[1];
            }
        })
        .catch(err => {
            console.error('OCR failed:', err);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'OCR Failed',
                text: 'Unable to scan the image. Please try again.'
            });
        });
}

// ===== Scan Using Uploaded Image =====
document.getElementById('upload-btn').addEventListener('click', () => {
    const fileInput = document.getElementById('book_cimage');
    fileInput.click();
    fileInput.onchange = function() {
        if(fileInput.files.length === 0) return;
        const file = fileInput.files[0];

        // Check file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please select an image smaller than 5MB.'
            });
            return;
        }

        updateCoverPreview(file);

        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.onload = () => runOCR(img);
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    };
});

// ===== Scan Using Camera =====
let cameraStream;
document.getElementById('camera-btn').addEventListener('click', async () => {
    const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
    modal.show();

    const video = document.getElementById('camera-video');
    cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
    video.srcObject = cameraStream;
});

document.getElementById('capture-btn').addEventListener('click', () => {
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    runOCR(canvas);

    // Stop camera
    cameraStream.getTracks().forEach(track => track.stop());
    bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();

    // Set captured image as book cover
    canvas.toBlob(blob => {
        const fileInput = document.getElementById('book_cimage');
        const file = new File([blob], "cover.png", { type: "image/png" });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        updateCoverPreview(file);
    });
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
