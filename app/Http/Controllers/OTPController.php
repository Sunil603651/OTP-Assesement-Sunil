<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        //dd($request->email);
        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        // Store hashed OTP
        OTP::create([
            'user_id' => $user->id,
            'code' => Hash::make($otp),
            'expired_at' => $expiresAt,
        ]);

        // Send OTP via email
        Mail::raw("Your OTP is: $otp. It expires at $expiresAt.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your One-Time Password');
        });

        return redirect()->route('otp.verify.form')->with('email', $user->email);
    }

    public function showVerifyForm()
    {
        return view('otp.verify');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();
        $otpRecord = OTP::where('user_id', $user->id)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord || !Hash::check($request->otp, $otpRecord->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        // OTP is valid, clear it
        $otpRecord->delete();

        // Optionally log the user in or redirect
        return redirect()->route('otp.success')->with('success', 'OTP verified successfully!');
    }

    public function showSuccess()
    {
        return view('otp.success');
    }
}


?>