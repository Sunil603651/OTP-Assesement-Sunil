@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Success</h2>
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            <a href="{{ route('otp.request.form') }}" class="btn btn-primary">Request Another OTP</a>
        </div>
    </div>
@endsection