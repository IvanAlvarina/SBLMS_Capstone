@extends('Layouts.vuexy')

@section('title', 'Book Details')

@section('content')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<style>
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

<!-- Book Details Card -->
<div class="card shadow-sm p-4">
  <div class="row g-4">
    
    <!-- Left: Book Cover -->
    <div class="col-md-4 text-center">
      <img src="{{ asset('assets/' . $book->book_cimage) }}" 
           alt="{{ $book->book_title }}" 
           class="book-cover-lg mb-3">
      <div class="d-grid gap-2">

        <div class="mt-auto">
            @if(in_array($book->book_id, $userBorrows))
                {{-- Already requested → show pending --}}
                <button class="btn btn-sm btn-label-warning w-100" disabled>
                    <i class="ti ti-clock me-1"></i> Pending Approval
                </button>
            @else
                @if(Auth::user()->role !== 'super-admin')
                    {{-- Super Admin → can manage book --}}
                    {{-- Can request borrow --}}
                    <form action="{{ route('browsebook.borrow', $book->book_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success w-100">
                            <i class="ti ti-book me-1"></i> Borrow Book
                        </button>
                    </form>
                @endif
            @endif
        </div>


        <a href="{{ route('browsebook.index') }}" class="btn">
          <i class="ti ti-arrow-left me-1"></i> Back to Browse
        </a>
      </div>
    </div>

    <!-- Right: Book Info -->
    <div class="col-md-8">
      <h2 class="fw-bold mb-2">{{ $book->book_title }}</h2>
      <p class="text-muted mb-4">by <span class="fw-semibold">{{ $book->book_author }}</span></p>
      
      <div class="mb-3">
        <span class="badge bg-label-primary px-3 py-2 me-2">
          <i class="ti ti-category me-1"></i> {{ $book->book_genre ?? 'Uncategorized' }}
        </span>
        <span class="badge bg-label-success px-3 py-2">
          <i class="ti ti-check me-1"></i> {{ $book->book_status }}
        </span>
      </div>

      <hr>

      <h5 class="fw-semibold mb-2">Description</h5>
      <p class="text-secondary" style="line-height: 1.6;">
        {{ $book->book_description ?? 'No description available.' }}
      </p>

      <div class="row mt-4">
        <div class="col-md-6 mb-3">
          <p class="detail-label mb-1"><i class="ti ti-barcode me-1"></i> ISBN</p>
          <p>{{ $book->book_isbn ?? 'N/A' }}</p>
        </div>
        <div class="col-md-6 mb-3">
          <p class="detail-label mb-1"><i class="ti ti-calendar me-1"></i> Published</p>
          <p>{{ $book->book_yearpub ?? 'Unknown' }}</p>
        </div>
        <div class="col-md-6 mb-3">
          <p class="detail-label mb-1"><i class="ti ti-building me-1"></i> Publisher</p>
          <p>{{ $book->book_author ?? 'N/A' }}</p>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection
