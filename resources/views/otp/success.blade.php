@extends('layouts.app')

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center"
        style="background-color: #FFE4E1;">
        <div class="col-md-6 col-sm-10">
            <div class="form-container p-5 bg-white rounded-lg border border-light shadow-sm">
                <h2 class="text-center mb-4" style="color: #1E3A8A; font-weight: bold;">Success</h2>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="text-center">
                    <a href="{{ route('otp.request.form') }}"
                        class="btn btn-success rounded-pill px-4 py-2 d-flex align-items-center justify-content-center mx-auto"
                        style="background-color: #38A169; border: none;">
                        <span class="me-2">+</span> Request Another OTP
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .btn-success:hover {
            background-color: #2F855A;
        }
    </style>
@endsection