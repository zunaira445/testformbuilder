<?php
// FILE PATH: app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ── Show Profile Page ─────────────────────────────────────
    public function show()
    {
        return view('profile.show');
    }

    // ── Update Profile Info ───────────────────────────────────
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if ($user->isStudent()) {
            $rules['city']        = 'nullable|string|max:100';
            $rules['roll_number'] = 'nullable|string|max:50';
        }

        $request->validate($rules);

        $data = $request->only('name', 'phone', 'institution');
        $data['dark_mode'] = $request->boolean('dark_mode');

        if ($user->isStudent()) {
            $data['city']        = $request->city;
            $data['roll_number'] = $request->roll_number;
        }

        // ── Profile Picture Upload ────────────────────────────
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path          = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        // ── FIXED: Only one redirect with one flash message ───
        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    // ── Update Password ───────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'current_password.required' => 'Please enter your current password.',
            'password.min'              => 'New password must be at least 8 characters.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // ── FIXED: redirect prevents double flash ─────────────
        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully.');
    }

    // ── Remove Avatar ─────────────────────────────────────────
    public function removeAvatar()
    {
        $user = Auth::user();
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->update(['avatar' => null]);
        return back()->with('success', 'Profile picture removed.');
    }
}