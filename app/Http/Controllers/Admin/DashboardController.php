<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{User, Test, TestAttempt, Payment, UserSubscription};

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'total_users'      => User::count(),
            'students'         => User::where('role','student')->count(),
            'instructors'      => User::where('role','instructor')->count(),
            'total_tests'      => Test::count(),
            'total_attempts'   => TestAttempt::count(),
            'pending_payments' => Payment::where('status','pending')->count(),
            'revenue'          => Payment::where('status','approved')->sum('amount'),
            'active_subs'      => UserSubscription::where('is_active',true)->count(),
        ];
        $recentUsers    = User::latest()->take(5)->get();
        $recentPayments = Payment::with(['user','plan'])->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats','recentUsers','recentPayments'));
    }
}