<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OTPController extends Controller
{
    public function index()
    {
        return view('otp.request');
    }

    public function showRequestForm()
    {
        return view('otp.request');
    }

    public function requestOTP(Request $request)
    {
        // Validate the email (but don't check if it exists in the users table)
        $request->validate(['email' => 'required|email']);

        $email = $request->email;

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15); // 15-minute validity

        // Clean up expired OTPs for this email
        OTP::where('email', $email)
            ->where('expired_at', '<', now())
            ->delete();

        // Store hashed OTP
        OTP::create([
            'email' => $email,
            'user_id' => null, // No user_id since we're not checking the users table
            'code' => Hash::make($otp),
            'expired_at' => $expiresAt,
        ]);

        // Send OTP via email
        Mail::raw("Your OTP is: $otp. It expires after 15 minutes.", function ($message) use ($email) {
            $message->from('sunilb1906@gmail.com', config('app.name'))
                ->to($email)
                ->subject('Your One-Time Password');
        });

        return redirect()->route('otp.verify.form')->with('email', $email);
    }

    public function showVerifyForm()
    {
        return view('otp.verify');
    }

    public function verifyOTP(Request $request)
    {
        // Combine the 6 input fields into a single OTP
        $otp = '';
        for ($i = 1; $i <= 6; $i++) {
            $otp .= $request->input("otp_$i");
        }

        // Validate the OTP
        $request->merge(['otp' => $otp]);
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $email = $request->email;
        $otpRecord = OTP::where('email', $email)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        // Validation checks
        if (!$otpRecord) {
            return redirect()->back()->with('error', 'No valid OTP found or OTP has expired.')->with('email', $email);
        }

        if (!Hash::check($otp, $otpRecord->code)) {
            return redirect()->back()->with('error', 'Invalid OTP.')->with('email', $email);
        }

        // OTP is valid, mark it as used by deleting it
        $otpRecord->delete();

        return redirect()->route('otp.success')->with('success', 'OTP verified successfully!');
    }

    public function showSuccess()
    {
        return view('otp.success');
    }
}
?>