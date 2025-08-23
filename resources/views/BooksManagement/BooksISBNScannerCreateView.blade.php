@extends('Layouts.vuexy')

@section('title', 'ISBN Scanner')

@section('content')
<div class="card">
    <div class="card-body text-center">
        <h4 class="mb-3">ISBN Scanner (Mobile-Friendly)</h4>
        <p>Align the ISBN barcode inside the box to scan. It will automatically fill the book form:</p>

        <!-- Scanner container -->
        <div id="scanner-container" style="position: relative; width: 100%; max-width: 400px; margin: auto;">
            <video id="video" style="width: 100%; border: 1px solid #ccc; border-radius: 8px;"></video>
            <canvas id="canvas" style="position: absolute; top: 0; left: 0;"></canvas>

            <!-- Center overlay box -->
            <div style="
                position: absolute;
                top: 50%;
                left: 50%;
                width: 80%;
                height: 100px;
                border: 3px dashed #00bfff;
                transform: translate(-50%, -50%);
                pointer-events: none;
                border-radius: 8px;
            "></div>
        </div>

        <p class="mt-3">Latest Scanned ISBN: <strong id="isbn-result">None</strong></p>

        <form id="add-book-isbn" action="{{ route('books-management.create') }}" method="GET">
            <input type="hidden" name="isbn" id="isbn-input">
            <button type="submit" class="btn btn-primary mt-2" id="use-isbn-btn" disabled>Use ISBN</button>
        </form>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isbnResult = document.getElementById('isbn-result');
    const isbnInput = document.getElementById('isbn-input');
    const useBtn = document.getElementById('use-isbn-btn');
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
            if (code.length === 13) {
                isbnResult.textContent = code;
                isbnInput.value = code;
                useBtn.disabled = false;

                // Fetch book details from ISBN API (OpenLibrary)
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

    function fetchBookData(isbn) {
        fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&format=json&jscmd=data`)
            .then(response => response.json())
            .then(data => {
                const bookKey = `ISBN:${isbn}`;
                if (data[bookKey]) {
                    const book = data[bookKey];

                    // Redirect to create form with prefilled query params
                    const params = new URLSearchParams({
                        isbn: isbn,
                        title: book.title || '',
                        author: (book.authors && book.authors.map(a => a.name).join(', ')) || ''
                    });
                    window.location.href = '{{ route("books-management.create") }}?' + params.toString();
                }
            })
            .catch(err => console.error('Error fetching book data:', err));
    }

    setTimeout(startScanner, 500);
});
</script>
@endpush
