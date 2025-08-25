@extends('Layouts.vuexy')

@section('title', 'Borrowed Books Requests')

@section('content')

@push('page-styles')

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/spinkit/spinkit.css') }}" />

<style>
    #page-loader.hidden {
        display: none !important;
    }
</style>

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

<!-- Page Loader (Spinkit Circle) -->
<div id="page-loader" class="d-flex justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 hidden" style="z-index: 1050; background-color: rgba(0, 0, 0, 0.5);">
    <div class="sk-circle sk-primary">
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
    </div>
</div>
    
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pending Borrow Requests</h5>
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
                        <th>Requested At</th>
                        <th>Approved At</th>
                        <th>Due Date</th>
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
                ajax: "{{ route('borrow-books.json') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'book_title', name: 'book.book_title' },
                    { data: 'user_name', name: 'user.fullname' },
                    { data: 'role', name: 'user.role' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'approved_at', name: 'approved_at' },
                    { data: 'due_date', name: 'due_date' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // optional: confirmation for approve
            $(document).on('submit', '.approve-form', function(e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: "Approve Request?",
                    text: "This will approve the borrow request.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes, approve it",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                         $('#page-loader').removeClass('hidden');
                    }
                });
            });
        });
    </script>
@endpush
