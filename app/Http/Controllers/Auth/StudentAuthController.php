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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class StudentAuthController extends Controller
{
    public function showLogin()    { return view('auth.student-login'); }
    public function showRegister() { return view('auth.student-register'); }

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

        // User create karo (unverified)
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

        // OTP generate karo aur bhejo — IMMEDIATELY (no queue)
        $sent = static::sendOtp($user->email);

        if (!$sent) {
            // OTP send fail hogi to user delete karo taake dobara register kar sake
            $user->delete();
            return back()
                ->withInput()
                ->withErrors(['email' => 'We could not send a verification email to this address. Please check the email and try again.']);
        }

        // Session mein email store karo (verify screen ke liye)
        session(['otp_email' => $user->email, 'otp_role' => 'student']);

        return redirect()->route('otp.verify.form')
            ->with('info', 'A 6-digit verification code has been sent to your email address. Please check your inbox (and spam folder).');
    }

    // ── LOGIN ─────────────────────────────────────────────────
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
                return back()->withErrors(['email' => 'These credentials are not valid for student login.']);
            }

            // Email verify check
            if (!$user->is_email_verified) {
                Auth::logout();
                static::sendOtp($user->email);
                session(['otp_email' => $user->email, 'otp_role' => 'student']);
                return redirect()->route('otp.verify.form')
                    ->with('info', 'Please verify your email first. A new verification code has been sent.');
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

    // ── HELPER: OTP Send — FIXED (returns bool for success check) ─
    public static function sendOtp(string $email): bool
    {
        try {
            // Purane OTPs delete karo
            EmailOtpToken::where('email', $email)->delete();

            // 6-digit OTP generate karo
            $otp  = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user = User::where('email', $email)->first();

            // DB mein save karo — expire in 5 minutes
            EmailOtpToken::create([
                'email'      => $email,
                'otp'        => $otp,
                'expires_at' => now()->addMinutes(5),
            ]);

            // Email IMMEDIATELY bhejo (no queue — connection: sync)
            Mail::to($email)->send(new OtpMail($otp, $user?->name ?? 'User'));

            return true;

        } catch (\Exception $e) {
            Log::error('OTP Mail Send Failed for ' . $email . ': ' . $e->getMessage());
            return false;
        }
    }
}