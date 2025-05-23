@extends('layouts.app')

@section('content')
    <div class="row justify-content-center min-vh-100 align-items-center"
        style="background: linear-gradient(135deg, #e0eafc, #cfdef3);">
        <div class="col-md-6 col-sm-10">
            <div class="card shadow-lg border-0 rounded-lg" style="background-color: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 text-primary">Verify OTP</h2>

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
                    <div id="otp-error" class="alert alert-danger d-none" role="alert"></div>

                    <form id="otp-form" action="{{ route('otp.verify') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-secondary">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg"
                                value="{{ session('email') }}" required readonly>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Enter OTP</label>
                            <div class="d-flex justify-content-between gap-2">
                                @for ($i = 1; $i <= 6; $i++)
                                    <input type="text" name="otp_{{ $i }}" id="otp_{{ $i }}"
                                        class="form-control form-control-lg text-center otp-input" maxlength="1" pattern="[0-9]"
                                        required oninput="moveToNext(this, {{ $i }})" onpaste="handlePaste(event)">
                                @endfor
                            </div>
                            @error('otp')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-lg d-none">Verify OTP</button>
                            <div id="loading-spinner" class="spinner-border text-primary d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus next input box
        function moveToNext(current, index) {
            const value = current.value;
            // Only allow numeric values
            if (!/^[0-9]$/.test(value)) {
                current.value = '';
                return;
            }

            // Move to next input if current is filled
            if (value && index < 6) {
                document.getElementById(`otp_${index + 1}`).focus();
            }

            // Check if all inputs are filled
            const allFilled = Array.from(document.querySelectorAll('.otp-input'))
                .every(input => input.value.length === 1 && /^[0-9]$/.test(input.value));

            if (allFilled) {
                document.getElementById('loading-spinner').classList.remove('d-none');
                document.getElementById('otp-form').submit();
            }
        }

        // Handle paste functionality
        function handlePaste(event) {
            event.preventDefault();
            const pasteData = (event.clipboardData || window.clipboardData).getData('text').trim();
            if (/^\d{6}$/.test(pasteData)) {
                const digits = pasteData.split('');
                for (let i = 0; i < 6; i++) {
                    document.getElementById(`otp_${i + 1}`).value = digits[i];
                }
                document.getElementById('loading-spinner').classList.remove('d-none');
                document.getElementById('otp-form').submit();
            } else {
                showError('Please paste a valid 6-digit OTP');
            }
        }

        // Show error message
        function showError(message) {
            const errorDiv = document.getElementById('otp-error');
            errorDiv.textContent = message;
            errorDiv.classList.remove('d-none');
            setTimeout(() => errorDiv.classList.add('d-none'), 5000);
        }

        // Keyboard navigation support
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    inputs[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });
    </script>
@endsection