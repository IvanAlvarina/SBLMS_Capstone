@extends('Layouts.auth')

@section('title', 'Register')

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
                        <img src="{{ asset('assets/bgpicture/wallpaper.jpg') }}" alt="Slide 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/bgpicture/wallpaper2.jpg') }}" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/bgpicture/wallpaper3.jpg') }}" alt="Slide 3">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/bgpicture/wallpaper4.jpg') }}" alt="Slide 4">
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

        <!-- Register -->
        <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
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
        <!-- /Register -->
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





