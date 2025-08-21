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

{{-- admin dashboard --}}
@if(auth()->user()->hasRole('super-admin'))

  <div class="row mb-4">
      <div class="col-12">
          <div class="card">
              <div class="card-header">
                  <h5>Users Stats</h5>
              </div>
              <div class="card-body">
                  <div class="row">
                      <!-- Faculty -->
                      <div class="col-md-6 mb-3">
                          <div class="card text-center clickable-card" data-type="faculty" style="cursor:pointer;">
                              <div class="card-body">
                                  <h5 class="card-title">Faculty</h5>
                                  <h2 class="text-primary">
                                      <span class="badge bg-primary me-2">
                                          <i class="bi bi-people-fill"></i>
                                      </span>
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
                                      <span class="badge bg-success me-2">
                                          <i class="bi bi-person-fill"></i>
                                      </span>
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

  <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-header">
                  <h5>Book Stats</h5>
              </div>
              <div class="card-body">
                  <div class="row">
                      @php
                          $cards = [
                              ['id' => 'total', 'title' => 'Total Books', 'count' => $totalBooks, 'color' => 'primary', 'icon' => 'bi-book'],
                              ['id' => 'available', 'title' => 'Available Books', 'count' => $availableBooks, 'color' => 'success', 'icon' => 'bi-check-circle'],
                              ['id' => 'borrowed', 'title' => 'Borrowed Books', 'count' => $borrowedBooks, 'color' => 'warning', 'icon' => 'bi-arrow-left-circle'],
                              ['id' => 'reserved', 'title' => 'Reserved Books', 'count' => $reservedBooks, 'color' => 'info', 'icon' => 'bi-clock'],
                              ['id' => 'removed', 'title' => 'Removed Books', 'count' => $removedBooks, 'color' => 'secondary', 'icon' => 'bi-trash'],
                          ];
                      @endphp

                      @foreach ($cards as $card)
                      <div class="col-md-4 col-sm-6 mb-3">
                          <div class="card text-center clickable-card" data-type="{{ $card['id'] }}" style="cursor:pointer;">
                              <div class="card-body">
                                  <h5 class="card-title">{{ $card['title'] }}</h5>
                                  <h2 class="text-{{ $card['color'] }}">
                                      <span class="badge bg-{{ $card['color'] }} me-2">
                                          <i class="bi {{ $card['icon'] }}"></i>
                                      </span>
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
  </div>

@endif

{{-- dashboard for student and faculty --}}
@if(auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty'))

  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-12">
        <div 
          class="card text-white border-0 shadow-sm position-relative" 
          style="background-image: url('{{ asset('storage/assets/wallpaper.jpg') }}'); 
                background-size: cover; 
                background-position: center; 
                height: 400px;
                border-radius: 1rem; 
                transform: translateY(-10px) scale(1.02);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
          
          <!-- Dark overlay -->
          <div class="position-absolute top-0 start-0 w-100 h-100" 
              style="background: rgba(0,0,0,0.5); border-radius: 0.5rem;">
          </div>

          <!-- Content -->
          <div class="card-body d-flex flex-column align-items-center justify-content-center text-center position-relative">
            <h1 class="text-white fw-bold">
              SBC Online Library Management System
            </h1>
            <h3 class="text-white mt-3">
            Your gateway to knowledge
            </h3>
          </div>

        </div>

        <div class="mt-4 mb-4 text-center">
          <div class="card-header">
            <h2 class="mb-3">Welcome to your Online Library!</h2>
          </div>
          <div class="card-body">
            <p class="mb-0">You will be able to read books of different topics and age-appropriateness through this shared site.</p>
        </div>
        </div>

        <div class="mt-5">
          <div class="row align-items-center">
            
            <!-- Logo on the left -->
            <div class="col-md-4 text-center mb-4 mb-md-0">
              <img src="{{ asset('storage/assets/logo.jpg') }}" alt="SBC Logo" 
                  class="img-fluid" style="max-height: 200px; border-radius: 0.5rem;">
            </div>

            <!-- History on the right -->
            <div class="col-md-8">
              <h2 class="fw-bold mb-3 text-white">History</h2>
              <p class="text-white">
                St. Bridget College (SBC), founded in 1947 by the Religious of the Good Shepherd (RGS), 
                is a private Catholic institution located in Batangas City, Philippines. 
                It was established to provide quality education and formation grounded in Christian values. 
                Throughout the years, SBC has built a legacy of academic excellence and social responsibility, 
                offering various educational programs from elementary to tertiary levels.
              </p>
              <p class="text-white">
                The institution aims to nurture the holistic development of its students by fostering a learning environment 
                that encourages intellectual, emotional, and spiritual growth. SBC takes pride in its rich history, 
                values-based education, and commitment to community service.
              </p>
              <p class="text-white mb-0">
                Today, SBC continues to serve the youth of Batangas and surrounding areas, 
                providing them with the tools to succeed in their academic, professional, and personal lives.
              </p>
            </div>
            
          </div>
        </div>

      </div>
    </div>
  </div>
  
@endif

{{-- Force Password Change Modal --}}
@if(session('force_password_change') && (auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty')))
<div class="modal fade show" id="forceChangePasswordModal" tabindex="-1" aria-modal="true" role="dialog" style="display:block; background: rgba(0,0,0,0.6);">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('password.forceChange') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Change Your Password</h5>
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
          {{-- Update Password Button --}}
          <button type="submit" class="btn btn-primary">Update Password</button>

          {{-- Logout Button --}}
          <a href="{{ route('logout') }}" 
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
             class="btn btn-danger">
             Logout
          </a>
        </div>
      </form>

      {{-- Hidden Logout Form --}}
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
  </div>
</div>
@endif

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modalContent">
          <p class="text-center">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('page-scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
      let modal = document.getElementById('forceChangePasswordModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
        modal.setAttribute('data-bs-backdrop', 'static');
        modal.setAttribute('data-bs-keyboard', 'false');
      }
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
      const modalTitle = document.getElementById('detailsModalLabel');
      const modalContent = document.getElementById('modalContent');

      document.querySelectorAll('.clickable-card').forEach(card => {
          card.addEventListener('click', function () {
              const type = this.getAttribute('data-type');
              modalTitle.textContent = this.querySelector('.card-title').textContent;
              modalContent.innerHTML = '<p class="text-center">Loading...</p>';

              modal.show();

              let url;
              let isUser = false;

              // Determine which endpoint to call based on the type clicked
              if (['faculty', 'student'].includes(type)) {
                  url = `/dashboard/users-data?type=${type}`;
                  isUser = true;
              } else {
                  url = `/dashboard/books-data?type=${type}`;
              }

              fetch(url)
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          if(data.data.length === 0){
                              modalContent.innerHTML = '<p class="text-center">No records found.</p>';
                              return;
                          }

                          let html = '';

                          if (isUser) {
                              // User table
                              html = `
                                  <table id="modalTable" class="table table-striped" style="width:100%">
                                      <thead>
                                          <tr>
                                              <th>ID</th>
                                              <th>Full Name</th>
                                              <th>Email</th>
                                              <th>Role</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                              `;

                              data.data.forEach(user => {
                                  html += `
                                      <tr>
                                          <td>${user.id}</td>
                                          <td>${user.fullname}</td>
                                          <td>${user.email}</td>
                                          <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                                      </tr>
                                  `;
                              });

                              html += '</tbody></table>';

                          } else {
                              // Book table
                              html = `
                                  <table id="modalTable" class="table table-striped" style="width:100%">
                                      <thead>
                                          <tr>
                                              <th>Book ID</th>
                                              <th>Title</th>
                                              <th>Author</th>
                                              <th>Status</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                              `;

                              data.data.forEach(book => {
                                  html += `
                                      <tr>
                                          <td>${book.book_id}</td>
                                          <td>${book.book_title}</td>
                                          <td>${book.book_author}</td>
                                          <td>${book.book_status}</td>
                                      </tr>
                                  `;
                              });

                              html += '</tbody></table>';
                          }

                          modalContent.innerHTML = html;

                          // Initialize DataTable
                          $('#modalTable').DataTable({
                              pageLength: 10,
                              lengthChange: false,
                              searching: false,
                              ordering: false,
                              destroy: true // allow re-init
                          });

                      } else {
                          modalContent.innerHTML = '<p class="text-danger text-center">Failed to load data.</p>';
                      }
                  })
                  .catch(() => {
                      modalContent.innerHTML = '<p class="text-danger text-center">Error fetching data.</p>';
                  });
          });
      });
  });
</script>
@endpush
