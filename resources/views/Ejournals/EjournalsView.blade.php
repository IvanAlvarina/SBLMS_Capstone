@extends('Layouts.vuexy')

@section('title', 'E-Journals')

@section('content')
<div class="row">

  <!-- Welcome Section -->
  <div class="col-12">
    <div class="card mb-4 bg-light-primary">
      <div class="card-body text-center">
        <i class="ti ti-journal mb-2" style="font-size: 2rem;"></i>
        <h3 class="fw-bold">Welcome to E-Journals</h3>
        <p class="mb-0">
          Access a wide range of scholarly journals, research publications, and academic articles.  
          Browse the resources below to discover reliable materials for your studies and professional growth.
        </p>
      </div>
    </div>
  </div>

  <!-- Journals Section -->
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center">
        <i class="ti ti-book menu-icon me-1"></i>
        <h4 class="card-title mb-0">Available Journals</h4>
      </div>
      <div class="card-body">
        <ul class="list-unstyled mb-0">
          @forelse ($journals as $journal)
            <li>
              <i class="ti ti-link me-1 text-primary"></i>
              <a href="{{ $journal->url }}" target="_blank">{{ $journal->name }}</a>
            </li>
          @empty
            <li>No journals available at the moment.</li>
          @endforelse
        </ul>

        <!-- Pagination -->
        <div class="mt-3">
          {{ $journals->links('pagination::bootstrap-5') }}
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
