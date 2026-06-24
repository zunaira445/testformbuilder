<?php
// FILE PATH: app/Http/Controllers/Auth/StudentAuthController.php

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

class StudentAuthController extends Controller
{
    public function showLogin()    { return view('auth.student-login'); }
    public function showRegister() { return view('auth.student-register'); }

    // ── REGISTER ──────────────────────────────────────────────
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
            'password.symbols' => 'Password must contain at least one special character (e.g. @, #, !).',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'student',
            'phone'             => $request->phone,
            'institution'       => $request->institution,
            'city'              => $request->city,
            'roll_number'       => $request->roll_number,
            'is_email_verified' => false,
        ]);

        // Send OTP immediately
        self::sendOtp($user->email, $user->name);

        session(['otp_email' => $user->email, 'otp_role' => 'student']);

        return redirect()->route('otp.verify.form')
            ->with('info', 'A 6-digit verification code has been sent to your email. Please check your inbox.');
    }

    // ── LOGIN ──────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->role !== 'student') {
                Auth::logout();
                return back()->withErrors(['email' => 'Invalid credentials for student login.']);
            }

            // Email not verified — resend OTP and redirect
            if (!$user->is_email_verified) {
                Auth::logout();
                self::sendOtp($user->email, $user->name);
                session(['otp_email' => $user->email, 'otp_role' => 'student']);
                return redirect()->route('otp.verify.form')
                    ->with('info', 'Please verify your email first. A new code has been sent to your inbox.');
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been disabled. Please contact support.']);
            }

            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors(['email' => 'The email or password is incorrect.']);
    }

    // ── LOGOUT ────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }

    // ── SEND OTP (static, called by both auth controllers) ────
    public static function sendOtp(string $email, string $name = 'User'): void
    {
        // Delete any old unused OTPs for this email
        EmailOtpToken::where('email', $email)->delete();

        // Generate 6-digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store with 5-minute expiry
        EmailOtpToken::create([
            'email'      => $email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(5),
            'used'       => false,
        ]);

        // Send immediately (no queue)
        Mail::to($email)->send(new OtpMail($otp, $name));
    }
}