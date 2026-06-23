<?php
// FILE PATH: app/Http/Controllers/Auth/InstructorAuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtpToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class InstructorAuthController extends Controller
{
    public function showLogin()    { return view('auth.instructor-login'); }
    public function showRegister() { return view('auth.instructor-register'); }

    // ── REGISTER ─────────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => [
                'required', 'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'password.min'     => 'Password must be at least 8 characters.',
            'password.mixed'   => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'instructor',
            'phone'             => $request->phone,
            'institution'       => $request->institution,
            'is_email_verified' => false,
        ]);

        // OTP bhejo
        StudentAuthController::sendOtp($user->email);

        session(['otp_email' => $user->email, 'otp_role' => 'instructor']);

        return redirect()->route('otp.verify.form')
            ->with('info', 'Aapke email par 6-digit OTP bheja gaya hai.');
    }

    // ── LOGIN ─────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->role !== 'instructor') {
                Auth::logout();
                return back()->withErrors(['email' => 'Invalid credentials for instructor login.']);
            }

            // Email verify check
            if (!$user->is_email_verified) {
                Auth::logout();
                StudentAuthController::sendOtp($user->email);
                session(['otp_email' => $user->email, 'otp_role' => 'instructor']);
                return redirect()->route('otp.verify.form')
                    ->with('info', 'Pehle apna email verify karein. OTP bheja gaya hai.');
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been disabled. Contact support.']);
            }

            $request->session()->regenerate();
            return redirect()->route('instructor.dashboard');
        }

        return back()->withErrors(['email' => 'Email or password is incorrect.']);
    }

    // ── LOGOUT ────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('instructor.login');
    }
}