@extends('Layouts.auth')

@section('title', 'Login')

@section('content')

    <style>
        /* ✅ Make left side act like wallpaper */
        .auth-cover-bg {
            position: relative;
            width: 100%;
            height: 100vh; /* full viewport height */
            overflow: hidden;
        }

        #authCarousel,
        #authCarousel .carousel-inner,
        #authCarousel .carousel-item {
            height: 100%;
        }

        #authCarousel img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* fill + crop wallpaper style */
        }
    </style>

    <!-- Left Side Image Carousel -->
    <div class="d-none d-lg-flex col-lg-7 p-0">
        <div class="auth-cover-bg auth-cover-bg-color w-100">
            <div id="authCarousel" class="carousel slide carousel-fade w-100 h-100"
                 data-bs-ride="carousel" data-bs-interval="4000">
                
                <div class="carousel-inner h-100">
                    <div class="carousel-item active">
                        <img src="{{ asset('storage/assets/wallpaper.jpg') }}" alt="Slide 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('storage/assets/wallpaper2.jpg') }}" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('storage/assets/wallpaper3.jpg') }}" alt="Slide 3">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('storage/assets/wallpaper4.jpg') }}" alt="Slide 4">
                    </div>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#authCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#authCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="3"></button>
                </div>

            </div>
        </div>
    </div>

    <!-- Login Form -->
    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
            <!-- Logo -->
            <div class="app-brand mb-4">
                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo"></span>
                </a>
            </div>

            <h4 class="mb-1">Welcome to SBLMS👋</h4>
            <p class="mb-4">Please sign-in to your account</p>

            {{-- ✅ Success Notification --}}
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

            <form method="POST" action="#">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text"
                           class="form-control @error('username') is-invalid @enderror"
                           id="username"
                           name="username"
                           placeholder="Enter your username"
                           autofocus required />
                    @error('username')
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
                <a href="">
                    <span>Create an account</span>
                </a>
            </p>
        </div>
    </div>
@endsection
