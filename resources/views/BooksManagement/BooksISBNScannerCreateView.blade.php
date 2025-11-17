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

                if (book.cover) {
                    updateCoverPreview(book.cover.large || book.cover.medium || book.cover.small);
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
            decoder: { readers: ["ean_reader", "code_128_reader", "code_39_reader"] },
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

    if (file.size > 5 * 1024 * 1024) {
        Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Please select an image smaller than 5MB.' });
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            for (let i = 0; i < data.length; i += 4) {
                const gray = 0.299 * data[i] + 0.587 * data[i+1] + 0.114 * data[i+2];
                const contrast = 1.5;
                const factor = (259 * (contrast + 255)) / (255 * (259 - contrast));
                const contrasted = factor * (gray - 128) + 128;
                const val = Math.min(255, Math.max(0, contrasted));
                data[i] = data[i+1] = data[i+2] = val;
            }
            ctx.putImageData(imageData, 0, 0);

            Quagga.decodeSingle({
                src: canvas.toDataURL(),
                numOfWorkers: 0,
                inputStream: { size: 800 },
                decoder: { readers: ["ean_reader", "code_128_reader", "code_39_reader"] }
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
                        if (result.isConfirmed) document.getElementById('manual-isbn-btn').click();
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
    if (!classification) { numberField.value = ''; return; }

    const base = classification.split('–')[0];
    fetch('/books-management/get-next-dewey-number?classification=' + encodeURIComponent(classification))
        .then(res => res.json())
        .then(data => {
            if (data.next_number) numberField.value = data.next_number;
            else numberField.value = base + '.' + Math.floor(Math.random()*100+1).toString().padStart(2,'0');
        })
        .catch(() => {
            numberField.value = base + '.' + Math.floor(Math.random()*100+1).toString().padStart(2,'0');
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
