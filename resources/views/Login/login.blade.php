@extends('Layouts.auth')

@section('title', 'Login')

@section('content')

    <style>
        /* 2-panel login page styles */
        .left-panel {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .right-panel {
            background-color: #0260A8;
            display: flex;
            align-items: center;
            padding: 2rem;
            min-height: 100vh;
        }
        .welcome-message {
            text-align: center;
            color: #000000ff;
        }
        .logo-image {
            max-width: 500px;
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
    <div class="d-none d-lg-flex col-lg-7 left-panel">
        <div class="welcome-message">
            <img src="{{ asset('logo.webp') }}" alt="SBLMS Logo" class="logo-image">
            <h1>Welcome to SBLMS</h1>
            <p>Your gateway to knowledge and learning resources.</p>
            <p>Please sign-in to your account</p>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="d-flex col-12 col-lg-5 right-panel">
        <div class="w-px-400 mx-auto">
            <!-- Logo -->
            <div class="app-brand mb-4">
                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo"></span>
                </a>
            </div>

            <h4 class="mb-1"></h4>
            <p class="mb-4"></p>

            {{-- Success Notification --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-checks me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Login errors --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.authenticate') }}" id="formAuthentication" class="mb-3">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Student no. / Faculty no.</label>
                    <input type="text"
                           class="form-control @error('student_no') is-invalid @enderror"
                           id="username"
                           name="username"
                           placeholder="Enter your student no. or faculty no."
                           autofocus required />
                    @error('student_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        <a href="#">
                            <small>Forgot Password?</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="••••••••••••"
                               required />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                        <label class="form-check-label" for="remember-me">Remember Me</label>
                    </div>
                </div>

                <button class="btn btn-primary d-grid w-100">Sign in</button>
            </form>

            <p class="text-center mt-3">
                <span>New on our platform?</span>
                <a href="{{ route('login.register') }}" class="link fw-semibold">
                    <span>Create an account</span>
                </a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        console.log('test');

        // const studentNoInput = document.getElementById('student_no');

        // if (studentNoInput) {
        //     studentNoInput.addEventListener('input', function(e) {
        //         // keep only numbers
        //         let value = e.target.value.replace(/\D/g, '');

        //         // enforce max length (2+4+3 = 9 digits total)
        //         value = value.substring(0, 9);

        //         let formatted = '';

        //         if (value.length > 0) {
        //             formatted += value.substring(0, 2); // first 2 digits
        //         }
        //         if (value.length > 2) {
        //             formatted += '-' + value.substring(2, Math.min(6, value.length)); // next up to 4 digits
        //         }
        //         if (value.length > 6) {
        //             formatted += '-' + value.substring(6, Math.min(9, value.length)); // last up to 3 digits
        //         }

        //         e.target.value = formatted;
        //     });
        // }
    });
</script>
@endpush
