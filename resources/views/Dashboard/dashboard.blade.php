@extends('Layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

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

{{-- Prevent closing modal --}}
<script>
  document.addEventListener("DOMContentLoaded", function() {
      let modal = document.getElementById('forceChangePasswordModal');
      modal.classList.add('show');
      modal.style.display = 'block';
      modal.setAttribute('data-bs-backdrop', 'static');
      modal.setAttribute('data-bs-keyboard', 'false');
  });
</script>
@endif


@include('partials.chatbot')

@endsection
