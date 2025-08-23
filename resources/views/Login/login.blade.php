@extends('Layouts.auth')

@section('title', 'Landing Page')

@section('content')

<style>
    body {
        background-color: #f8f9fa;
    }

    .landing-container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        background: url("{{ asset('storage/assets/wallpaper.jpg') }}") center/cover no-repeat;
        position: relative;
    }

    .landing-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
    }

    .landing-content {
        position: relative;
        z-index: 2;
        color: white;
    }

    .app-logo {
        height: 70px;
        margin-bottom: 1rem;
    }

    .btn-login {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
        border-radius: 30px;
    }

    /* --- Modal Styling --- */
    .modal-content {
        border-radius: 16px;
        background: rgba(0, 0, 0, 0.85); /* same dark tone as register */
        color: #fff;
        padding: 1.8rem;
        border: none;
    }

    .modal-header {
        border: none;
        justify-content: center;
    }

    .modal-title {
        font-weight: bold;
        font-size: 1.25rem;
        text-align: center;
    }

    .form-label {
        font-weight: 500;
        color: #ddd;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-color: #0d6efd;
        box-shadow: none;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .input-group-text {
        background: rgba(255, 255, 255, 0.08);
        border: none;
        color: #bbb;
        cursor: pointer;
    }

    .btn-primary {
        border-radius: 30px;
    }

    .modal-footer {
        border: none;
    }

    .alert {
        font-size: 0.9rem;
    }
</style>

<!-- Landing Page -->
<div class="landing-container">
    <div class="landing-overlay"></div>
    <div class="landing-content">
        <img src="{{ asset('storage/assets/logo.jpg') }}" alt="SBC Logo" class="app-logo">
        <h1 class="fw-bold">Welcome to SBLMS</h1>
        <p class="lead mb-4">St. Bridget College Library Management System</p>
        <button class="btn btn-primary btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
            Log In
        </button>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade @if(session('showLogin')) show @endif" 
     id="loginModal" 
     tabindex="-1" 
     aria-labelledby="loginModalLabel" 
     aria-hidden="true" 
     @if(session('showLogin')) style="display:block;" @endif>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="loginModalLabel">Sign In</h5>
            </div>

            <div class="modal-body">
                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login.authenticate') }}" id="formAuthentication">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Student/Faculty Number</label>
                        <input type="text" 
                               class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" placeholder="e.g. 22-0066-927" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label d-flex justify-content-between">
                            <span>Password</span>
                            <a href="#"><small>Forgot password?</small></a>
                        </label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" placeholder="••••••••••••" required>
                            <span class="input-group-text">
                                <i class="ti ti-eye-off"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                        <label class="form-check-label" for="remember-me">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    New here?
                    <a href="{{ route('login.register') }}" class="fw-semibold text-light">Create an account</a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.input-group-text').forEach(el => {
        el.addEventListener('click', function () {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="ti ti-eye"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="ti ti-eye-off"></i>';
            }
        });
    });

    // Auto-open modal if session flag is set
    @if(session('showLogin'))
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    @endif
</script>
@endpush
