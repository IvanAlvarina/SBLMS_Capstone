@extends('Layouts.vuexy')

@section('title', 'E-Journal List')

@section('content')

{{-- Notifications --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-checks me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">E-Journals</h5>
    <a href="{{ route('ejournals.add') }}" class="btn btn-primary btn-sm">
      <i class="ti ti-plus me-1"></i> Add New
    </a>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table id="ejournals-table" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>URL</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {{-- DataTables will fill this --}}
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
  <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
  <script>
    $(function () {
      $('#ejournals-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('ejournals.getData') }}',
        columns: [
          { data: 'name', name: 'name' },
          { data: 'url', name: 'url' },
          { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
          { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
      });
    });

    // SweetAlert2 confirmation before delete
    $(document).on('submit', '.delete-form', function(e) {
      e.preventDefault();
      let form = this;

      Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the journal.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        customClass: {
          confirmButton: 'btn btn-danger me-2',
          cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    // SweetAlert2 session messages
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false
      });
    @endif

    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
        confirmButtonClass: 'btn btn-danger',
        buttonsStyling: false
      });
    @endif
  </script>
@endpush


@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<style>
<style>
  table.dataTable td {
    vertical-align: middle;
  }
</style>
@endpush
