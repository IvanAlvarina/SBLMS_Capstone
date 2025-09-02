@extends('Layouts.vuexy')

@section('title', 'News & Magazines')

@section('content')
<div class="row">

  <!-- Welcome Section -->
  <div class="col-12">
    <div class="card mb-4 bg-light-info">
      <div class="card-body text-center">
        <h3 class="fw-bold">Welcome to News & Magazines</h3>
        <p class="mb-0">
          Stay updated with the latest news, articles, and magazines from trusted local and international sources.  
          Browse the links below to access reliable media outlets and scientific publications.
        </p>
      </div>
    </div>
  </div>

  <!-- News & Magazines Section -->
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <i class="ti ti-news menu-icon me-1"></i>
        <h4 class="card-title mb-0">News & Magazines</h4>
      </div>
      <div class="card-body">
        <ul class="list-unstyled mb-0">
          @forelse ($newsMagazines as $news)
            <li>
              <i class="ti ti-news me-1 text-primary"></i>
              <a href="{{ $news->url }}" target="_blank">{{ $news->name }}</a>
            </li>
          @empty
            <li>No active News & Magazine sources available.</li>
          @endforelse
        </ul>

        <!-- Pagination -->
        <div class="mt-3">
           {{ $newsMagazines->links('pagination::bootstrap-5') }}
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
    margin-bottom: 8px;
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
