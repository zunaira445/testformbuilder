<?php
// FILE PATH: app/Http/Controllers/Auth/StudentAuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentAuthController extends Controller
{
    public function showLogin()    { return view('auth.student-login'); }
    public function showRegister() { return view('auth.student-register'); }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // requires upper + lower case
                    ->numbers()     // requires at least one number
                    ->symbols(),    // requires at least one symbol
            ],
        ], [
            'password.min'       => 'Password must be at least 8 characters.',
            'password.mixed'     => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers'   => 'Password must contain at least one number.',
            'password.symbols'   => 'Password must contain at least one special character (e.g. @, #, !, $).',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'student',
            'phone'       => $request->phone,
            'institution' => $request->institution,
            'city'        => $request->city,
            'roll_number' => $request->roll_number,
        ]);

        Auth::login($user);
        return redirect()->route('student.dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            if (Auth::user()->role !== 'student') {
                Auth::logout();
                return back()->withErrors(['email' => 'Invalid credentials for student login.']);
            }
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been disabled. Contact support.']);
            }
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors(['email' => 'Email or password is incorrect.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }
}