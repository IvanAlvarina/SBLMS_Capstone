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
@endpush

<div class="card-datatable table-responsive pt-0">
    <table class="datatables-basic table">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Book Title</th>
                <th>Book Author</th>
                <th>Book Genre</th>
                <th>Book Date of Publish</th>
                <th>Book ISBN</th>
                <th>Book Status</th>
                <th>Book Cover Image</th>
                <th>Date Added into Library</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script>
// Function to format ISBN (ISBN-10 or ISBN-13)
function formatISBN(isbn) {
    if (!isbn) return ''; // Return empty string if no ISBN

    // Remove any existing hyphens or spaces
    let digits = isbn.replace(/[-\s]/g, '');

    if (digits.length === 10) {
        // Format ISBN-10: 1-234-56789-X
        return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
    } else if (digits.length === 13) {
        // Format ISBN-13: 978-1-23-456789-0
        return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
    } else {
        return isbn; // Return raw if length is not 10 or 13
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
                { data: 'book_id' },
                { data: 'book_title' },
                { data: 'book_author' },
                { data: 'book_genre' },
                { data: 'book_yearpub' },
                { 
                    data: 'book_isbn', 
                    render: function(data, type, row) {
                        return formatISBN(data); // Format ISBN in table
                    }
                },
                { data: 'book_status' },
                {
                    data: 'book_cimage',
                    render: function(data, type, row) {
                        if (data) {
                            let imageUrl = '{{ asset("assets") }}/' + data;
                            return '<img src="' + imageUrl + '" alt="Book Cover" style="height: 60px; width: auto; border-radius: 4px;">';
                        } else {
                            return '<span class="text-muted">No Image</span>';
                        }
                    }
                },
                { data: 'book_dateadded' },
                { data: 'action', orderable: false, searchable: false }
            ],
            dom: '<"card-header flex-column flex-md-row align-items-center"<"head-label text-center"><"dt-filter-status me-auto"><"dt-action-buttons text-end"B>>' +
                '<"row"<"col-sm-14 col-md-6"l><"col-sm-14 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                't<"row"<"col-sm-14 col-md-6"i><"col-sm-14 col-md-6"p>>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
                    text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: ['excel']
                },
                {
                    text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Book</span>',
                    className: 'btn btn-primary waves-effect waves-light me-2',
                    action: function () {
                        window.location.href = '{{ route("books-management.create") }}';
                    }
                },
                {
                    text: '<i class="ti ti-camera me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Book (OCR)</span>',
                    className: 'btn btn-secondary waves-effect waves-light me-2',
                    action: function () {
                        window.location.href = '{{ route("books-management.ocr") }}';
                    }
                },
                {
                    text: '<i class="ti ti-barcode me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Book (ISBN)</span>',
                    className: 'btn btn-info waves-effect waves-light',
                    action: function () {
                        window.location.href = '{{ route("books-management.isbnscanner") }}';
                    }
                }
            ],
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 10
        });

        $('div.dt-filter-status').html(`
            <label for="status-filter" class="me-2 mb-0" style="line-height: 38px;">Filter by Status:</label>
            <select id="status-filter" class="form-select form-select-sm" style="width: 180px; display: inline-block;">
                <option value="active" selected>Active Books</option>
                <option value="removed">Removed Books</option>
                <option value="all">All Books</option>
            </select>
        `);

        $('#status-filter').on('change', function () {
            table.ajax.reload(null, false);
        });
    }
});

// Delete (soft delete) with SweetAlert
$(document).on('click', '.delete-btn', function () {
    let bookId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This book will be marked as Removed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.isConfirmed) {
            $.ajax({
                url: '/books-management/' + bookId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed!',
                            text: 'The book has been removed.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        $('.datatables-basic').DataTable().ajax.reload(null, false);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            });
        }
    });
});
</script>

@endpush
