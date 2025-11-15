@extends('Layouts.vuexy')

@section('title', 'List')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

<style>
/* Pagination buttons design */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background-color: #1e1e2d !important;  /* match your table dark theme */
    color: #fff !important;
    border: none;
    padding: 5px 12px;
    margin: 0 2px;
    border-radius: 4px;
    cursor: pointer;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #0d6efd !important; /* current page blue */
    color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #2a2a3c !important;
    color: #fff !important;
}

/* Fix dropdown scrollbar */
.dropdown-menu {
    overflow: visible !important;
    position: absolute !important;
    z-index: 1050 !important;
}

.dropdown-toggle {
    overflow: visible !important;
}

.book-cover-lg {
    max-height: 420px;
    object-fit: cover;
    border-radius: 1rem;
    width: 100%;
}

.detail-label {
    font-weight: 600;
    color: #5e5873;
}
</style>
@endpush

<div class="card-datatable table-responsive pt-0">
    <table class="datatables-basic table">
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Genre</th>
                <th>Dewey Specific No.</th>
                <th>Cutter-Sanborn</th>
                <th>Year Published</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- View Book Modal -->
<div class="modal fade" id="viewBookModal" tabindex="-1" aria-labelledby="viewBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewBookModalLabel"><i class="ti ti-book me-2"></i>Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Left: Book Cover -->
                    <div class="col-md-4 text-center">
                        <div id="book-image">
                            <img id="book-cover" src="" alt="Book Cover" class="book-cover-lg mb-3">
                        </div>
                        <div class="d-grid gap-2">
                            <a href="#" id="edit-book-btn" class="btn btn-sm btn-primary">
                                <i class="ti ti-edit me-1"></i> Edit Book
                            </a>
                        </div>
                    </div>

                    <!-- Right: Book Info -->
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2" id="modal-book-title"></h2>
                        <p class="text-muted mb-4">by <span class="fw-semibold" id="modal-book-author"></span></p>

                        <div class="mb-3">
                            <span class="badge bg-label-primary px-3 py-2 me-2" id="modal-book-genre">
                                <i class="ti ti-category me-1"></i>
                            </span>
                            <span class="badge bg-label-success px-3 py-2" id="modal-book-status">
                                <i class="ti ti-check me-1"></i>
                            </span>
                        </div>

                        <hr>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <p class="detail-label mb-1"><i class="ti ti-barcode me-1"></i> Book ID</p>
                                <p id="book-id"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="detail-label mb-1"><i class="ti ti-building me-1"></i> Location</p>
                                <p id="book-location"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="detail-label mb-1"><i class="ti ti-tag me-1"></i> Dewey Classification</p>
                                <p id="book-dewey"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="detail-label mb-1"><i class="ti ti-calendar me-1"></i> Year Published</p>
                                <p id="book-yearpub"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="detail-label mb-1"><i class="ti ti-hash me-1"></i> ISBN</p>
                                <p id="book-isbn"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
function formatISBN(isbn) {
    if (!isbn) return '';
    let digits = isbn.replace(/[-\s]/g, '');
    if (digits.length === 10) {
        return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    } else if (digits.length === 13) {
        return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
    } else {
        return isbn;
    }
}

$(function () {
    var dt_basic_table = $('.datatables-basic');

    if (dt_basic_table.length) {
        var table = dt_basic_table.DataTable({
            ajax: {
                url: '{{ route("books-management.json") }}',
                type: 'GET',
                data: function (d) {
                    d.status = $('#status-filter').val();
                },
                error: function(xhr, error, code) {
                    console.log('AJAX Error:', xhr, error, code);
                    alert('Error loading data: ' + xhr.responseText);
                }
            },
            columns: [
                { data: 'book_title', width: '300px' },
                { data: 'book_genre', width: '150px' },
                { data: 'dewey_number', width: '150px' },
                { data: 'cutter_sanborn', width: '150px' },
                { data: 'book_yearpub', width: '120px' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (row.book_status === 'Removed') {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-success restore-btn" data-id="${row.book_id}">
                                    <i class="ti ti-refresh me-1"></i> Restore
                                </button>
                            </div>`;
                        } else {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-info view-btn" data-id="${row.book_id}">
                                    <i class="ti ti-eye me-1"></i> View
                                </button>
                                <a class="btn btn-sm btn-primary" href="/books-management/${row.book_id}/edit">
                                    <i class="ti ti-edit me-1"></i> Edit
                                </a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.book_id}">
                                    <i class="ti ti-trash me-1"></i> Remove
                                </button>
                            </div>`;
                        }
                    }
                }
            ],
            dom: '<"card-header flex-column flex-md-row align-items-center"<"head-label text-center"><"dt-filter-status me-auto"><"dt-action-buttons text-end"B>>' +
                 '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                 't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
                    text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        { extend: 'excelHtml5', className: 'btn btn-success', text: 'Export to Excel', title: 'Books List', exportOptions: { columns: ':visible' } },
                        { extend: 'pdfHtml5', className: 'btn btn-danger', text: 'Export to PDF', title: 'Books List', orientation: 'landscape', pageSize: 'A4', exportOptions: { columns: ':visible' } }
                    ]
                },
                { text: '<i class="ti ti-plus me-sm-1"></i> Add Book', className: 'btn btn-primary waves-effect waves-light me-2', action: () => window.location.href='{{ route("books-management.create") }}' },
                { text: '<i class="ti ti-camera me-sm-1"></i> Add Book (OCR)', className: 'btn btn-secondary waves-effect waves-light me-2', action: () => window.location.href='{{ route("books-management.ocr") }}' },
                { text: '<i class="ti ti-barcode me-sm-1"></i> Add Book (ISBN)', className: 'btn btn-info waves-effect waves-light', action: () => window.location.href='{{ route("books-management.isbnscanner") }}' }
            ],
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            processing: true,
            serverSide: true,
            pageLength: 10,
            scrollX: true,
            autoWidth: false
        });

        // Status filter dropdown
        $('div.dt-filter-status').html(` 
            <label for="status-filter" class="me-2 mb-0" style="line-height:38px;">Filter by Status:</label>
            <select id="status-filter" class="form-select form-select-sm" style="width:200px; display:inline-block;">
                <option value="active" selected>Active Books</option>
                <option value="Available">Available</option>
                <option value="Borrowed">Borrowed</option>
                <option value="Reserved">Reserved</option>
            </select>
        `);

        $('#status-filter').on('change', function () {
            table.ajax.reload();
        });
    }
});

// Delete (soft delete)
$(document).on('click', '.delete-btn', function () {
    let bookId = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: "This book will be marked as Removed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-label-secondary' },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/books-management/' + bookId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire({ icon:'success', title:'Removed!', text:'The book has been removed.', customClass:{confirmButton:'btn btn-success'} });
                    $('.datatables-basic').DataTable().ajax.reload();
                }
            });
        }
    });
});

// Restore
$(document).on('click', '.restore-btn', function () {
    let bookId = $(this).data('id');
    Swal.fire({
        title: 'Restore Book?',
        text: "This will mark the book as Available again.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-success me-2', cancelButton: 'btn btn-label-secondary' },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/books-management/' + bookId + '/restore',
                type: 'PATCH',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire({ icon:'success', title:'Restored!', text:'The book has been restored.', customClass:{confirmButton:'btn btn-success'} });
                    $('.datatables-basic').DataTable().ajax.reload();
                }
            });
        }
    });
});

// View Book Details
$(document).on('click', '.view-btn', function () {
    let bookId = $(this).data('id');

    $.ajax({
        url: '/books-management/' + bookId + '/details',
        type: 'GET',
        success: function(response) {
            $('#modal-book-title').text(response.book_title);
            $('#modal-book-author').text(response.book_author);
            $('#modal-book-genre').html('<i class="ti ti-category me-1"></i> ' + (response.book_genre || 'Uncategorized'));
            $('#modal-book-status').html('<i class="ti ti-check me-1"></i> ' + response.book_status);
            $('#book-id').text(response.book_id);
            $('#book-location').text(response.book_location);
            $('#book-dewey').text(response.dewey_classification || 'Not classified');
            $('#book-yearpub').text(response.book_yearpub);
            $('#book-isbn').text(formatISBN(response.book_isbn));

            // Update edit button href
            $('#edit-book-btn').attr('href', '/books-management/' + bookId + '/edit');

            if (response.book_cimage) {
                $('#book-cover').attr('src', '{{ asset("assets") }}/' + response.book_cimage);
                $('#book-image').show();
            } else {
                $('#book-image').hide();
            }

            $('#viewBookModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load book details.'
            });
        }
    });
});
</script>
@endpush
