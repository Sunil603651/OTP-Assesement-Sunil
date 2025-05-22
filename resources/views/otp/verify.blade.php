@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Verify OTP</h2>
            <form action="{{ route('otp.verify') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ session('email') }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="otp" class="form-label">OTP</label>
                    <input type="text" name="otp" id="otp" class="form-control" required>
                    @error('otp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Verify OTP</button>
            </form>
        </div>
    </div>
@endsection