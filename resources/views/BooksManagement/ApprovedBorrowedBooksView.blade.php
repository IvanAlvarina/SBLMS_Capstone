@extends('Layouts.vuexy')

@section('title', 'Borrowed Books Requests')

@section('content')

@push('page-styles')

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

{{--  Success Notification --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-checks me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Error Notification --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- spinner --}}
@include('components.loader');
    
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Approved Borrow Books</h5>
    </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="borrowBooksTable" class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Requested By</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Due At</th>
                            <th>Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
</div>
@endsection

@push('page-scripts')
    {{-- SweetAlert --}}
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script>
        
        $(document).ready(function () {
            // Ensure loader is hidden initially
            $('#page-loader').addClass('hidden');
        });

    </script>

    <script>
        $(function() {
            $('#borrowBooksTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('borrow-books.json.approved') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'book_title', name: 'book.book_title' },
                    { data: 'user_name', name: 'user.fullname' },
                    { data: 'role', name: 'user.role' },
                    { data: 'status', name: 'status' },
                    { data: 'due_date', name: 'due_date' },
                    { data: 'days_left', name: 'daysRemaining'}, 
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $(document).on('submit', '.approve-form', function(e) {
                    e.preventDefault();
                    let form = this;

                    Swal.fire({
                        title: "Mark as Completed?",
                        text: "Are you sure you want to complete this borrow? The book will be marked as available again.",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Yes, complete it",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#page-loader').removeClass('hidden');

                            $.ajax({
                                url: form.action,
                                method: form.method,
                                data: $(form).serialize(),
                                success: function(response) {
                                    $('#page-loader').addClass('hidden');
                                    if (response.success) {
                                        Swal.fire({
                                            title: "Completed!",
                                            text: response.message,
                                            icon: "success",
                                            confirmButtonText: "OK"
                                        });
                                        $('#borrowBooksTable').DataTable().ajax.reload(null, false); // refresh table
                                    } else {
                                        Swal.fire("Error", response.message, "error");
                                    }
                                },
                                error: function(xhr) {
                                    $('#page-loader').addClass('hidden');
                                    Swal.fire("Error", "Something went wrong while completing.", "error");
                                }
                            });
                        }
                    });
                });

        });
    </script>
@endpush
