@extends('Layouts.vuexy')

@section('title', 'OCR Scanner')

@section('content')

<div class="container text-center mt-4">
    <h3>OCR Scanner</h3>
    <video id="video" width="100%" style="max-width: 400px;" autoplay></video>
    <canvas id="canvas" style="display:none;"></canvas>
    <p>Detected Text: <span id="ocr-result">None</span></p>
    <button id="capture-btn" class="btn btn-primary mt-2">Capture & Scan</button>
</div>

@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.0.2/dist/tesseract.min.js"></script>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const resultContainer = document.getElementById('ocr-result');
const captureBtn = document.getElementById('capture-btn');

// Request camera access (mobile-friendly)
navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
.then(stream => {
    video.srcObject = stream;
})
.catch(err => {
    console.error(err);
    alert('Camera not supported or access denied.');
});

// Capture image & run OCR
captureBtn.addEventListener('click', () => {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    Tesseract.recognize(
        canvas,
        'eng',
        { logger: m => console.log(m) }
    ).then(({ data: { text } }) => {
        resultContainer.innerText = text.trim() || "No text detected";
    }).catch(err => {
        console.error(err);
        alert('OCR failed. Try again.');
    });
});
</script>
@endpush
