@extends('Layouts.vuexy')

@section('title', 'Browse Book')

@section('content')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<style>
  .book-card { transition: all 0.3s ease-in-out; }
  .book-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
  .book-cover { height: 220px; object-fit: cover; border-radius: 0.5rem; }

  /* Genre colors */
  .badge-genre-fiction       { background-color: #007bff !important; }
  .badge-genre-non-fiction   { background-color: #6c757d !important; }
  .badge-genre-scifi         { background-color: #17a2b8 !important; }
  .badge-genre-fantasy       { background-color: #fd7e14 !important; }
  .badge-genre-biography     { background-color: #28a745 !important; }
  .badge-genre-history       { background-color: #6610f2 !important; }
  .badge-genre-mystery       { background-color: #343a40 !important; }
  .badge-genre-romance       { background-color: #e83e8c !important; }
  .badge-genre-thriller      { background-color: #dc3545 !important; }
  .badge-genre-self-help     { background-color: #20c997 !important; }
  .badge-genre-children      { background-color: #ffc107 !important; color: #000 !important; }
  .badge-genre-technology    { background-color: #0dcaf0 !important; }
  .badge-genre-other         { background-color: #adb5bd !important; }
  .badge[class*="badge-genre-"] { color: #fff !important; cursor: pointer; }
</style>
@endpush

{{-- spinner --}}
@include('components.loader')

<!-- Search Bar -->
<div class="card mb-4">
  <div class="card-body">
    <div class="d-flex">
      <input type="text" id="search-input" class="form-control me-2" placeholder="Search books by title, author, or keyword...">
    </div>
  </div>
</div>

<!-- Books + Pagination -->
<div id="books-container">
  <div class="row g-4">
    @forelse($books as $book)
      <div class="col-md-3 col-sm-6">
        <div class="card book-card h-100">
          <img src="{{ $book->book_cimage ? asset('assets/' . $book->book_cimage) : asset('storage/assets/default-book.jpg') }}" 
               alt="{{ $book->book_title }}" 
               class="book-cover card-img-top">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title text-truncate">{{ $book->book_title }}</h5>
            <p class="text-muted mb-2">by {{ $book->book_author }}</p>

            @php
                $genreClasses = [
                    'fiction'         => 'badge-genre-fiction',
                    'non-fiction'     => 'badge-genre-non-fiction',
                    'science-fiction' => 'badge-genre-scifi',
                    'fantasy'         => 'badge-genre-fantasy',
                    'biography'       => 'badge-genre-biography',
                    'history'         => 'badge-genre-history',
                    'mystery'         => 'badge-genre-mystery',
                    'romance'         => 'badge-genre-romance',
                    'thriller'        => 'badge-genre-thriller',
                    'self-help'       => 'badge-genre-self-help',
                    'children'        => 'badge-genre-children',
                    'technology'      => 'badge-genre-technology',
                    'other'           => 'badge-genre-other',
                ];
                $genreKey = strtolower(str_replace(' ', '-', $book->book_genre));
                $badgeClass = $genreClasses[$genreKey] ?? 'badge-genre-other';
            @endphp

            <div class="mb-3">
              <span class="badge {{ $badgeClass }}">{{ $book->book_genre }}</span>
            </div>

            <div class="mt-auto">
              <a href="{{ route('browsebook.show', $book->book_id) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                <i class="ti ti-eye"></i> View Details
              </a>
              @if(in_array($book->book_id, $userBorrows))
                  <button class="btn btn-warning btn-sm w-100" disabled>
                      <i class="ti ti-clock"></i> Pending Approval
                  </button>
              @else

                 @if(Auth::user()->role !== 'super-admin')
                    <form action="{{ route('browsebook.borrow', $book->book_id) }}" method="POST" class="borrow-form">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm w-100 borrow-btn">
                            <i class="ti ti-book"></i> Borrow
                        </button>
                    </form>
                  @endif
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center">
        <p class="text-muted">No books found. Try searching with a different keyword.</p>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  <div class="mt-4">
    {{ $books->links('pagination::bootstrap-5') }}
  </div>
</div>

@endsection

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
$(document).ready(function(){
    function fetchBooks(query = '', page = 1) {
        $.ajax({
            url: "{{ route('browsebook.index') }}",
            type: "GET",
            data: { search: query, page: page },
            success: function(response) {
                $('#books-container').html($(response.html).find('#books-container').html());
            }
        });
    }

    // Search typing
    $('#search-input').on('keyup', function() {
        let query = $(this).val();
        fetchBooks(query, 1);
    });

    // Pagination AJAX
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        let query = $('#search-input').val();
        fetchBooks(query, page);
    });

    // SweetAlert2 confirmation before borrow
    $(document).on('submit', '.borrow-form', function(e) {
        e.preventDefault();
        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to borrow this book? this will send a request for approval.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, borrow it!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-success me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();

                $('#page-loader').removeClass('hidden');
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
});
</script>
@endpush
