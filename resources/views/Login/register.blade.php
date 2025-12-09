@extends('Layouts.auth')

@section('title', 'Register')

@section('content')

    <style>
        /* 2-panel register page styles */
        .left-panel {
            background-color: #0260A8;
            display: flex;
            align-items: center;
            padding: 2rem;
            min-height: 100vh;
        }
        .right-panel {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .welcome-message {
            text-align: center;
            color: #333;
        }
        .logo-image {
            max-width: 200px;
            height: auto;
            margin-bottom: 1rem;
        }
        .welcome-message h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .welcome-message p {
            font-size: 1.2rem;
            margin-bottom: 0;
        }
        .btn-primary {
            background-color: white !important;
            border-color: white !important;
            color: #0260A8 !important;
        }
        .btn-primary:hover {
            background-color: #f8f9fa !important;
            border-color: #f8f9fa !important;
            color: #0260A8 !important;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .form-label {
            color: white;
        }
        .text-center a {
            color: white;
        }
        .text-center a:hover {
            color: #f8f9fa;
        }
        @media (max-width: 991.98px) {
            .left-panel, .right-panel {
                min-height: auto;
                padding: 1rem;
            }
            .welcome-message h1 {
                font-size: 2rem;
            }
        }
    </style>

    <!-- Left Panel -->
    <div class="d-flex col-12 col-lg-5 left-panel">
          <div class="w-px-400 mx-auto">
            <!-- Logo -->
            <!-- /Logo -->
            <h4 class="mb-1">"Your Library, Organized & Effortless.”</h4>

            <form id="formAuthentication" class="mb-3" action="{{ route('login.store') }}" method="POST">
                @csrf

                {{-- Fullname --}}
                <div class="mb-3">
                    <label for="username" class="form-label">Full name</label>
                    <input
                        type="text"
                        class="form-control @error('username') is-invalid @enderror"
                        id="fullname"
                        name="fullname"
                        placeholder="Enter your Fullname"
                        value="{{ old('fullname') }}"
                        autofocus />
                    @error('fullname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Student no. --}}
                <div class="mb-3">
                    <label for="student_no" class="form-label">Student number</label>
                    <input
                        type="text"
                        class="form-control @error('student_no') is-invalid @enderror"
                        id="student_no"
                        name="student_no"
                        placeholder="Enter your Student no. eg: 22-0066-927"
                        value="{{ old('student_no') }}"
                        autofocus />
                    @error('student_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input
                        type="text"
                        class="form-control @error('address') is-invalid @enderror"
                        id="address"
                        name="address"
                        placeholder="Enter your address"
                        value="{{ old('address') }}"
                        autofocus />
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="text"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        value="{{ old('email') }}" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary d-grid w-100">Sign up</button>
            </form>


            <p class="text-center">
              <span>Already have an account?</span>
              <a href="{{route('login.index')}}" class="link fw-semibold">
                <span>Sign in instead</span>
              </a>
            </p>

          </div>
        </div>

    <!-- Right Panel -->
    <div class="d-none d-lg-flex col-lg-7 right-panel">
        <div class="welcome-message">
            <img src="{{ asset('logo.webp') }}" alt="SBLMS Logo" class="logo-image">
            <h1>Welcome to SBLMS</h1>
            <p>Your gateway to knowledge and learning resources.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        console.log('test');

        const studentNoInput = document.getElementById('student_no');

        if (studentNoInput) {
            studentNoInput.addEventListener('input', function(e) {
                // keep only numbers
                let value = e.target.value.replace(/\D/g, '');

                // enforce max length (2+4+3 = 9 digits total)
                value = value.substring(0, 9);

                let formatted = '';

                if (value.length > 0) {
                    formatted += value.substring(0, 2); // first 2 digits
                }
                if (value.length > 2) {
                    formatted += '-' + value.substring(2, Math.min(6, value.length)); // next up to 4 digits
                }
                if (value.length > 6) {
                    formatted += '-' + value.substring(6, Math.min(9, value.length)); // last up to 3 digits
                }

                e.target.value = formatted;
            });
        }
    });
</script>
@endpush





