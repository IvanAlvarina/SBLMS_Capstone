# TODO: Make Add Book using ISBN work like Add Book using OCR

## Tasks
- [x] Modify BooksISBNScannerCreateView.blade.php to include full form fields (title/author readonly, others editable) and book cover preview section
- [x] Add upload button for ISBN barcode images
- [x] Update JavaScript to fetch book data (title, author, cover) from OpenLibrary API after camera scanning
- [x] Update JavaScript to scan barcode from uploaded image using Quagga, then fetch data
- [x] Ensure form submits directly to store route
- [x] Improve barcode detection: add image preprocessing (grayscale, contrast), support ISBN-10, better error handling
- [x] Add manual ISBN input fallback when scanning fails
- [ ] Test camera scanning: scans ISBN, fills fields, displays cover
- [ ] Test upload scanning: uploads image, scans barcode, fills fields, displays cover
- [ ] Test manual ISBN input: enters ISBN, fetches data, fills fields
- [ ] Test form submission: saves book correctly
