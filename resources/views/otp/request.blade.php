@extends('layouts.app')

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center"
        style="background-color: #FFE4E1;">
        <div class="col-md-6 col-sm-10">
            <div class="form-container p-5 bg-white rounded-lg border border-light shadow-sm">
                <h2 class="text-center mb-4" style="color: #1E3A8A; font-weight: bold;">Request OTP</h2>

                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="request-form" action="{{ route('otp.request') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold" style="color: #2D3748;">Email</label>
                        <input type="email" name="email" id="email" class="form-control rounded-lg"
                            placeholder="Enter your email" value="{{ old('email') }}" required
                            style="border: 1px solid #E2E8F0; padding: 12px;">
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" id="submit-btn"
                            class="btn btn-success rounded-pill px-4 py-2 d-flex align-items-center justify-content-center mx-auto"
                            style="background-color: #38A169; border: none;">
                            <span class="me-2">+</span> Send OTP
                        </button>
                        <div id="loading-spinner" class="spinner-border text-success d-none mt-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-control:focus {
            border-color: #38A169;
            box-shadow: 0 0 0 0.2rem rgba(56, 161, 105, 0.25);
        }

        .btn-success:hover {
            background-color: #2F855A;
        }
    </style>

    <script>
        // Show loading spinner on form submission
        document.getElementById('request-form').addEventListener('submit', function () {
            document.getElementById('submit-btn').classList.add('d-none');
            document.getElementById('loading-spinner').classList.remove('d-none');
        });
    </script>
@endsection