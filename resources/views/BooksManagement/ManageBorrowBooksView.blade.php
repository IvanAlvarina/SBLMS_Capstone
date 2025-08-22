@extends('Layouts.vuexy')

@section('title', 'Borrowed Books Requests')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Pending Borrow Requests</h5>
  </div>

  <div class="card-body">
    @if($borrowedBooks->isEmpty())
      <div class="alert alert-info">
        No pending borrow requests found.
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Book Title</th>
              <th>Requested By</th>
              <th>Role</th>
              <th>Status</th>
              <th>Requested At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($borrowedBooks as $index => $borrow)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $borrow->book->book_title ?? 'N/A' }}</td>
                <td>
                  {{ $borrow->user->fullname }} -
                  @if($borrow->user->role === 'Student')
                      {{ $borrow->user->student_no ?? 'N/A' }}
                  @elseif($borrow->user->role === 'Faculty')
                      {{ $borrow->user->faculty_no ?? 'N/A' }}
                  @else
                      N/A
                  @endif
                </td>

                <td>
                  @if($borrow->user->role === 'Student')
                      <span class="badge bg-primary">{{ $borrow->user->role }}</span>
                  @elseif($borrow->user->role === 'Faculty')
                      <span class="badge bg-success">{{ $borrow->user->role }}</span>
                  @else
                      <span class="badge bg-secondary">{{ $borrow->user->role ?? 'User' }}</span>
                  @endif
                </td>

                <td>
                  <span class="badge bg-warning">{{ $borrow->status }}</span>
                </td>

                <td>{{ optional($borrow->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>

                <td>
                  <!-- 3-dot dropdown -->
                  <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-text-secondary dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                      <!-- Approve -->
                      <form action="{{ route('borrow-books.approve', $borrow->id) }}" method="POST" class="approve-form">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="dropdown-item text-success">
                          <i class="ti ti-check me-1"></i> Approve
                        </button>
                      </form>
                      <!-- Reject -->
                      <form action="" method="POST" class="reject-form">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="dropdown-item text-danger">
                          <i class="ti ti-x me-1"></i> Reject
                        </button>
                      </form>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif

    <!-- Pagination -->
    <div class="mt-3">
        {{ $borrowedBooks->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endpush
