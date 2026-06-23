<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware {
    public function handle(Request $request, Closure $next, string $role): mixed {
        if (!Auth::check()) {
            return redirect()->route('student.login');
        }
        if (Auth::user()->role !== $role) {
            abort(403, 'Access denied.');
        }
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Your account has been disabled.');
        }
        return $next($request);
    }
}