@extends('Layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

@push('page-styles')
<style>
  .clickable-card:hover {
      box-shadow: 0 0 12px rgba(0, 123, 255, 0.5);
      transform: translateY(-4px);
      transition: all 0.3s ease;
  }
</style>
@endpush

{{-- ================= ADMIN DASHBOARD ================= --}}
@if(auth()->user()->hasRole('super-admin'))

<!-- User Stats -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5>User Stats</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Faculty -->
          <div class="col-md-6 mb-3">
            <div class="card text-center clickable-card" data-type="faculty" style="cursor:pointer;">
              <div class="card-body">
                <h5 class="card-title">Faculty</h5>
                <h2 class="text-primary">
                  <span class="badge bg-primary me-2"><i class="bi bi-people-fill"></i></span>
                  {{ $facultyCount }}
                </h2>
              </div>
            </div>
          </div>
          <!-- Students -->
          <div class="col-md-6 mb-3">
            <div class="card text-center clickable-card" data-type="student" style="cursor:pointer;">
              <div class="card-body">
                <h5 class="card-title">Students</h5>
                <h2 class="text-success">
                  <span class="badge bg-success me-2"><i class="bi bi-person-fill"></i></span>
                  {{ $studentCount }}
                </h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@php
  $cards = [
    ['id'=>'total','title'=>'Total Books','count'=>$totalBooks,'color'=>'primary','icon'=>'bi-book'],
    ['id'=>'available','title'=>'Available Books','count'=>$availableBooks,'color'=>'success','icon'=>'bi-check-circle'],
    ['id'=>'borrowed','title'=>'Borrowed Books','count'=>$borrowedBooks,'color'=>'warning','icon'=>'bi-arrow-left-circle'],
    ['id'=>'reserved','title'=>'Reserved Books','count'=>$reservedBooks,'color'=>'info','icon'=>'bi-clock'],
    ['id'=>'removed','title'=>'Removed Books','count'=>$removedBooks,'color'=>'secondary','icon'=>'bi-trash'],
  ];
@endphp

<!-- Book Stats + Borrowing Chart Side by Side -->
<div class="row">
  <!-- Book Stats -->
  <div class="col-lg-8 col-md-12 mb-3">
    <div class="card">
      <div class="card-header"><h5>Book Stats</h5></div>
      <div class="card-body">
        <div class="row">
          @foreach ($cards as $card)
            <div class="col-md-4 col-sm-6 mb-3">
              <div class="card text-center clickable-card" data-type="{{ $card['id'] }}" style="cursor:pointer;">
                <div class="card-body">
                  <h5 class="card-title">{{ $card['title'] }}</h5>
                  <h2 class="text-{{ $card['color'] }}">
                    <span class="badge bg-{{ $card['color'] }} me-2"><i class="bi {{ $card['icon'] }}"></i></span>
                    {{ $card['count'] }}
                  </h2>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <!-- Borrowing Stats Chart -->
  <div class="col-lg-4 col-md-12 mb-3">
    <div class="card h-100">
      <div class="card-header"><h5>Borrowing Stats (Faculty vs Students)</h5></div>
      <div class="card-body d-flex align-items-center justify-content-center">
        <div style="max-width:300px; width:100%;">
          <canvas id="borrowStatsChart" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

@endif

{{-- ================= STUDENT / FACULTY DASHBOARD ================= --}}
@if(auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty'))

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-12">
      <!-- Hero Section -->
      <div class="card text-white border-0 shadow-sm position-relative"
        style="background-image: url('{{ asset('storage/assets/wallpaper.jpg') }}');
               background-size: cover; background-position: center;
               height: 400px; border-radius: 1rem;
               box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background: rgba(0,0,0,0.5); border-radius: 1rem;"></div>
        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center position-relative">
          <h1 class="text-white fw-bold">SBC Online Library Management System</h1>
          <h3 class="text-white mt-3">Your gateway to knowledge</h3>
        </div>
      </div>

      <!-- Welcome -->
      <div class="mt-4 mb-4 text-center">
        <h2 class="mb-3">Welcome to your Online Library!</h2>
        <p>You will be able to read books of different topics and age-appropriateness through this shared site.</p>
      </div>

      <!-- History -->
      <div class="mt-5">
        <div class="row align-items-center">
          <div class="col-md-4 text-center mb-4 mb-md-0">
            <img src="{{ asset('storage/assets/logo.jpg') }}" alt="SBC Logo"
              class="img-fluid" style="max-height: 200px; border-radius: 0.5rem;">
          </div>
          <div class="col-md-8">
            <h2 class="fw-bold mb-3 text-white">History</h2>
            <p class="text-white">
              St. Bridget College (SBC), founded in 1947 by the Religious of the Good Shepherd (RGS),
              is a private Catholic institution located in Batangas City, Philippines...
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@endif

{{-- ================= FORCE PASSWORD CHANGE ================= --}}
@if(session('force_password_change') && (auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty')))
<div class="modal fade" id="forceChangePasswordModal" tabindex="-1" aria-labelledby="forceChangePasswordLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('password.forceChange') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="forceChangePasswordLabel">Change Your Password</h5>
        </div>
        <div class="modal-body">
          <p>You must change your password before continuing.</p>
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Password</button>
          <a href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
             class="btn btn-danger">Logout</a>
        </div>
      </form>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
  </div>
</div>
@endif

{{-- ================= MODALS ================= --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modalContent"><p class="text-center">Loading...</p></div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  // Force Password Modal
  if (document.getElementById('forceChangePasswordModal')) {
    new bootstrap.Modal(document.getElementById('forceChangePasswordModal')).show();
  }

  // Details Modal + Fetch
  const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
  const modalTitle = document.getElementById('detailsModalLabel');
  const modalContent = document.getElementById('modalContent');

  document.querySelectorAll('.clickable-card').forEach(card => {
    card.addEventListener('click', function () {
      const type = this.getAttribute('data-type');
      modalTitle.textContent = this.querySelector('.card-title').textContent;
      modalContent.innerHTML = '<p class="text-center">Loading...</p>';
      modal.show();

      let url = (['faculty','student'].includes(type)) ? `/dashboard/users-data?type=${type}` : `/dashboard/books-data?type=${type}`;
      let isUser = ['faculty','student'].includes(type);

      fetch(url)
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            modalContent.innerHTML = '<p class="text-danger text-center">Failed to load data.</p>';
            return;
          }
          if (data.data.length === 0) {
            modalContent.innerHTML = '<p class="text-center">No records found.</p>';
            return;
          }

          let html = `<table id="modalTable" class="table table-striped" style="width:100%"><thead><tr>`;
          html += isUser ? '<th>ID</th><th>Full Name</th><th>Email</th><th>Role</th>' : '<th>Book ID</th><th>Title</th><th>Author</th><th>Status</th>';
          html += `</tr></thead><tbody>`;

          data.data.forEach(item => {
            if (isUser) {
              html += `<tr><td>${item.id}</td><td>${item.fullname}</td><td>${item.email}</td><td>${item.role}</td></tr>`;
            } else {
              html += `<tr><td>${item.book_id}</td><td>${item.book_title}</td><td>${item.book_author}</td><td>${item.book_status}</td></tr>`;
            }
          });

          html += '</tbody></table>';
          modalContent.innerHTML = html;

          $('#modalTable').DataTable({
            pageLength: 10, lengthChange: false, searching: false, ordering: false, destroy: true
          });
        })
        .catch(() => modalContent.innerHTML = '<p class="text-danger text-center">Error fetching data.</p>');
    });
  });

  // Borrowing Stats Chart
  @if(auth()->user()->hasRole('super-admin'))
  const borrowStatsChart = new Chart(document.getElementById('borrowStatsChart'), {
    type: 'doughnut',
    data: {
      labels: ['Faculty', 'Students'],
      datasets: [{
        label: 'Borrowed Books',
        data: [{{ $facultyBorrowed ?? 0 }}, {{ $studentBorrowed ?? 0 }}],
        backgroundColor: ['#0d6efd', '#198754']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });
  @endif
});
</script>

@if(auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty'))
  @include('partials.chatbot')
@endif
@endpush
