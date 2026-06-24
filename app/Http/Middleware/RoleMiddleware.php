<?php
// FILE PATH: app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        // Not logged in at all
        if (!Auth::check()) {
            $loginRoute = match($role) {
                'instructor' => 'instructor.login',
                'admin'      => 'student.login',
                default      => 'student.login',
            };
            return redirect()->route($loginRoute)
                ->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Wrong role trying to access
        if ($user->role !== $role) {
            abort(403, 'Access Denied. You do not have permission to access this page.');
        }

        // Account disabled
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $loginRoute = match($role) {
                'instructor' => 'instructor.login',
                default      => 'student.login',
            };

            return redirect()->route($loginRoute)
                ->with('error', 'Your account has been disabled. Please contact support for assistance.');
        }

        return $next($request);
    }
}