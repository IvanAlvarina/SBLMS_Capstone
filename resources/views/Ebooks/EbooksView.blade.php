@extends('Layouts.vuexy')

@section('title', 'E-Books')

@section('content')
<div class="row">

  <!-- Welcome Section -->
  <div class="col-12">
    <div class="card mb-4 bg-light-primary">
      <div class="card-body text-center">
        <h3 class="fw-bold">Welcome to E-Books</h3>
        <p class="mb-0">
          Explore a wide selection of educational and professional e-books from trusted platforms.  
          Use the links below to access free and premium digital resources for learning and research.
        </p>
      </div>
    </div>
  </div>

  <!-- E-Books Section -->
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center">
        <i class="ti ti-book-2 menu-icon me-1"></i>
        <h4 class="card-title mb-0">E-Books Collection</h4>
      </div>
      <div class="card-body">
        <ul class="list-unstyled mb-0">
          @foreach ($ebooks as $ebook)
            <li>
              <i class="ti ti-link me-1 text-primary"></i>
              <a href="{{ $ebook->url }}" target="_blank">{{ $ebook->name }}</a>
            </li>
          @endforeach
        </ul>

        <!-- Pagination -->
        <div class="mt-3">
          {{ $ebooks->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>

</div>

  @if(auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty'))

    @include('partials.chatbot');

  @endif
@endsection

@push('page-styles')
<style>
  .card-body ul li {
    margin-bottom: 6px;
    font-size: 0.95rem;
  }
  .card-body a {
    text-decoration: none;
    font-weight: 500;
  }
  .card-body a:hover {
    text-decoration: underline;
  }
</style>
@endpush
