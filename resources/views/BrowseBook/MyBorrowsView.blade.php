@extends('Layouts.vuexy')

@section('title', 'My Borrowed Books')

@section('content')

@push('page-styles')
<style>
  .status-badge {
    padding: 0.35rem 0.65rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #fff;
  }
  .status-pending { background-color: #ffc107; color: #000; }
  .status-approved { background-color: #28a745; }
  .status-returned { background-color: #6c757d; }
  .status-rejected { background-color: #dc3545; }
</style>
@endpush

<div class="card">
  <div class="card-header">
    <h4 class="card-title">My Borrowed Books</h4>
  </div>
  <div class="card-body">
    @if($borrows->count() > 0)
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Cover</th>
              <th>Title</th>
              <th>Author</th>
              <th>Status</th>
              <th>Borrowed On</th>
            </tr>
          </thead>
          <tbody>
            @foreach($borrows as $borrow)
              <tr>
                <td>
                  <img src="{{ $borrow->book->book_cimage ? asset('assets/' . $borrow->book->book_cimage) : asset('storage/assets/default-book.jpg') }}" 
                       alt="{{ $borrow->book->book_title }}" 
                       style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px;">
                </td>
                <td>{{ $borrow->book->book_title }}</td>
                <td>{{ $borrow->book->book_author }}</td>
                <td>
                  <span class="status-badge status-{{ strtolower($borrow->status) }}">
                    {{ $borrow->status }}
                  </span>
                </td>
                <td>{{ $borrow->created_at->format('M d, Y') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $borrows->links('pagination::bootstrap-5') }}
      </div>
    @else
      <p class="text-muted text-center">You haven’t borrowed any books yet.</p>
    @endif
  </div>
</div>

@endsection
