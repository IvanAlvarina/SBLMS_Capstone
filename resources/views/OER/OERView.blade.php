@extends('Layouts.vuexy')

@section('title', 'Online Educational Resources')

@section('content')
<div class="container mt-4">
    {{-- Welcome Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
            <h2 class="mb-2">Welcome to Online Educational Resources (OER)</h2>
            <p class="text-muted">
                Explore a collection of trusted educational websites, journals, and digital libraries to enhance your learning journey.
            </p>
        </div>
    </div>

    {{-- Resource Links --}}
    <div class="row">
        @foreach ($oers as $oer)
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title">
                            <i class="ti ti-books me-2 text-primary"></i>{{ $oer->name }}
                        </h5>
                        <a href="{{ $oer->url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-3">
                            Visit Resource
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $oers->links('pagination::bootstrap-5') }}
        
    </div>
</div>

  @if(auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty'))

    @include('partials.chatbot');

  @endif
@endsection
