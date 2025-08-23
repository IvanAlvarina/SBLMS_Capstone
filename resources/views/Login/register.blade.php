@extends('Layouts.auth')

@section('title', 'Register')

@section('content')

<style>
    body {
        background-color: #f8f9fa;
    }

    .register-container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        background: url("{{ asset('storage/assets/wallpaper.jpg') }}") center/cover no-repeat;
        position: relative;
    }

    .register-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
    }

    .register-content {
        position: relative;
        z-index: 2;
        background: rgba(0, 0, 0, 0.75); /* match login modal */
        color: #fff; /* white text for contrast */
        padding: 2rem;
        border-radius: 12px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        text-align: left;
    }

    .register-content .form-label {
        color: #fff; /* labels white */
    }

    .register-content .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
    }

    .register-content .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .app-logo {
        height: 60px;
        margin-bottom: 1rem;
    }

    .register-content a {
        color: #0d6efd; /* keep links visible */
    }
</style>

<!-- Register Page -->
<div class="register-container">
    <div class="register-overlay"></div>
    <div class="register-content">
        <!-- Logo + Heading -->
        <div class="text-center mb-3">
            <img src="{{ asset('storage/assets/logo.jpg') }}" alt="SBC Logo" class="app-logo">
            <h4 class="fw-bold">"Your Library, Organized & Effortless.”</h4>
        </div>

        <!-- Register Form -->
        <form id="formAuthentication" action="{{ route('login.store') }}" method="POST">
            @csrf

            {{-- Fullname --}}
            <div class="mb-3">
                <label for="fullname" class="form-label">Full name</label>
                <input
                    type="text"
                    class="form-control @error('fullname') is-invalid @enderror"
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
                    value="{{ old('student_no') }}" />
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
                    value="{{ old('address') }}" />
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

            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>

        <p class="text-center mt-3 mb-0">
            Already have an account?
            <a href="{{ route('login.index', ['showLogin' => 1]) }}" class="fw-semibold">Sign in instead</a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-format student number (XX-XXXX-XXX)
    const studentNoInput = document.getElementById('student_no');
    if (studentNoInput) {
        studentNoInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 9); // limit length
            let formatted = '';
            if (value.length > 0) {
                formatted += value.substring(0, 2);
            }
            if (value.length > 2) {
                formatted += '-' + value.substring(2, Math.min(6, value.length));
            }
            if (value.length > 6) {
                formatted += '-' + value.substring(6, Math.min(9, value.length));
            }
            e.target.value = formatted;
        });
    }
</script>
@endpush
