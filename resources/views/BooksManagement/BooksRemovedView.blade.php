@extends('Layouts.vuexy')

@section('title', 'Removed Books')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

<div class="card-datatable table-responsive pt-0">
    <table class="datatables-removed table">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Year Published</th>
                <th>ISBN</th>
                <th>Status</th>
                <th>Cover</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script>
$(function () {
    function formatISBN(isbn) {
        if (!isbn) return '';
        let digits = isbn.replace(/[-\s]/g, '');
        if (digits.length === 10) return digits.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
        if (digits.length === 13) return digits.replace(/(\d{3})(\d{1})(\d{2})(\d{6})(\d{1})/, '$1-$2-$3-$4-$5');
        return isbn;
    }

    var table = $('.datatables-removed').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("books-management.json.removed") }}',
            type: 'GET',
            error: function(xhr, error, code) {
                console.error('AJAX Error:', xhr, error, code);
                alert('Error loading data: ' + xhr.responseText);
            }
        },
        columns: [
            { data: 'book_id' },
            { data: 'book_title' },
            { data: 'book_author' },
            { data: 'book_genre' },
            { data: 'book_yearpub' },
            { data: 'book_isbn', render: function(data) { return formatISBN(data); } },
            { data: 'book_status', orderable: false, searchable: false },
            { data: 'book_cimage', render: function(data) {
                if (data) {
                    let imageUrl = '{{ asset("assets") }}/' + data;
                    return `<img src="${imageUrl}" alt="Cover" style="height:60px;width:auto;border-radius:4px;">`;
                }
                return '<span class="text-muted">No Image</span>';
            }, orderable: false, searchable: false },
            { data: null, orderable: false, searchable: false, render: function(row) {
                return `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item text-success restore-btn" data-id="${row.book_id}">
                                    <i class="ti ti-refresh me-1"></i> Restore
                                </button>
                            </li>
                        </ul>
                    </div>
                `;
            }}
        ],
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', className: 'btn btn-success', text: 'Export to Excel', title: 'Removed Books', exportOptions: { columns: ':visible' } },
            { extend: 'pdfHtml5', className: 'btn btn-danger', text: 'Export to PDF', title: 'Removed Books', orientation: 'landscape', pageSize: 'A4', exportOptions: { columns: ':visible' } }
        ],
        responsive: true,
        pageLength: 10
    });

    // Restore book
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
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/books-management/' + bookId + '/restore',
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Restored!',
                                text: 'The book has been restored.',
                                customClass: { confirmButton: 'btn btn-success' }
                            });
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            customClass: { confirmButton: 'btn btn-danger' }
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
