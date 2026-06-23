<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function index(Request $request) {
        $users = User::query()
            ->when($request->search, fn($q) =>
                $q->where('name','like',"%{$request->search}%")
                  ->orWhere('email','like',"%{$request->search}%"))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function toggle(User $user) {
        $user->update(['is_active' => !$user->is_active]);
        return back();
    }

    public function destroy(User $user) {
        if ($user->role === 'admin') abort(403);
        $user->delete();
        return back()->with('success', 'User delete ho gaya.');
    }
}