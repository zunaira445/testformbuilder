<?php
// FILE PATH: app/Http/Controllers/Auth/OtpController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtpToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    // ── Show OTP form ─────────────────────────────────────────
    public function showForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('student.login');
        }
        return view('auth.otp-verify');
    }

    // ── Verify OTP ────────────────────────────────────────────
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Please enter the OTP code.',
            'otp.size'     => 'The OTP must be exactly 6 digits.',
        ]);

        $email = session('otp_email');
        $role  = session('otp_role', 'student');

        if (!$email) {
            return redirect()->route('student.login')
                ->withErrors(['otp' => 'Your session has expired. Please login again.']);
        }

        // Latest unused, non-expired OTP check (5 minute window)
        $tokenRecord = EmailOtpToken::where('email', $email)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$tokenRecord || $tokenRecord->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'The OTP is incorrect or has expired. Please request a new one.']);
        }

        // OTP mark as used
        $tokenRecord->update(['used' => true]);

        // User verify karo
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('student.login')
                ->withErrors(['otp' => 'No account found with this email.']);
        }

        $user->update(['is_email_verified' => true]);

        // Session clear
        session()->forget(['otp_email', 'otp_role']);

        // Auto login
        Auth::login($user);
        $request->session()->regenerate();

        // Role ke hisaab se redirect
        return match($role) {
            'instructor' => redirect()->route('instructor.dashboard')
                ->with('success', 'Email verified successfully! Welcome to SWF Portal! 🎉'),
            default      => redirect()->route('student.dashboard')
                ->with('success', 'Email verified successfully! Welcome to SWF Portal! 🎉'),
        };
    }

    // ── Resend OTP ────────────────────────────────────────────
    public function resend(Request $request)
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('student.login');
        }

        $sent = StudentAuthController::sendOtp($email);

        if (!$sent) {
            return back()->withErrors(['otp' => 'Failed to send OTP. Please check your email address or try again.']);
        }

        return back()->with('info', 'A new verification code has been sent to your email. Please check your inbox and spam folder.');
    }
}