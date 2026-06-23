<?php
// FILE PATH: app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
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

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

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
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}