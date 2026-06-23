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
            'otp.required' => 'OTP daalna zaroori hai.',
            'otp.size'     => 'OTP 6 digits ka hona chahiye.',
        ]);

        $email = session('otp_email');
        $role  = session('otp_role', 'student');

        if (!$email) {
            return redirect()->route('student.login')
                ->withErrors(['otp' => 'Session expire ho gayi. Dobara login karein.']);
        }

        $tokenRecord = EmailOtpToken::where('email', $email)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$tokenRecord || $tokenRecord->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'OTP galat hai ya expire ho gaya. Dobara try karein.']);
        }

        // OTP mark as used
        $tokenRecord->update(['used' => true]);

        // User verify karo
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('student.login')
                ->withErrors(['otp' => 'User nahi mila.']);
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
                ->with('success', 'Email verify ho gaya! SWF Portal mein khush aamdeed! 🎉'),
            default      => redirect()->route('student.dashboard')
                ->with('success', 'Email verify ho gaya! SWF Portal mein khush aamdeed! 🎉'),
        };
    }

    // ── Resend OTP ────────────────────────────────────────────
    public function resend(Request $request)
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('student.login');
        }

        StudentAuthController::sendOtp($email);

        return back()->with('info', 'Naya OTP bhej diya gaya hai. Apna email check karein.');
    }
}