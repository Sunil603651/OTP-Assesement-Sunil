<?php

use App\Http\Controllers\OTPController;

Route::get('/', [OTPController::class, 'index']);

Route::get('/otp/request', [OTPController::class, 'showRequestForm'])->name('otp.request.form');
Route::post('/otp/request', [OTPController::class, 'requestOTP'])->name('otp.request');
Route::get('/otp/verify', [OTPController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/otp/verify', [OTPController::class, 'verifyOTP'])->name('otp.verify');
Route::get('/otp/success', [OTPController::class, 'showSuccess'])->name('otp.success');


?>