@extends('Layouts.vuexy')

@section('title', $journal->id ? 'E-Journal Edit' : 'E-Journal Create')

@section('content')
<div class="content-header row">
  <div class="content-header-left col-12 mb-2">
    <div class="row breadcrumbs-top">
      <div class="col-12">
        <h2 class="content-header-title float-start mb-0">
          {{ $journal->id ? 'Edit E-Journal' : 'Add E-Journal' }}
        </h2>
      </div>
    </div>
  </div>
</div>

<section id="form-layout">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">E-Journal Details</h4>
        </div>
        <div class="card-body">
          <form
            class="needs-validation"
            novalidate
            method="POST"
            action="{{ $journal->id ? route('ejournals.update', $journal->id) : route('ejournals.store') }}"
          >
            @csrf
            @if($journal->id)
              @method('PUT')
            @endif

            <div class="mb-1">
              <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
              <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $journal->name) }}"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="e.g., Journal of Machine Learning Research"
                required
              >
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @else
                <div class="invalid-feedback">Please provide a journal name.</div>
              @enderror
            </div>

            <div class="mb-2">
              <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
              <input
                type="url"
                id="url"
                name="url"
                value="{{ old('url', $journal->url) }}"
                class="form-control @error('url') is-invalid @enderror"
                placeholder="https://example.com"
                pattern="https?://.+"
                required
              >
              @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
              @else
                <div class="invalid-feedback">Please enter a valid URL starting with http:// or https://</div>
              @enderror
            </div>

            <div class="d-flex align-items-center gap-1">
              <button type="submit" class="btn btn-primary">
                {{ $journal->id ? 'Update' : 'Save' }}
              </button>
              <a href="{{ route('ejournals.list') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('page-scripts')
<script>
  (function () {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();
</script>
@endpush
