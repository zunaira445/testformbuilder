{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/admin/users/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title','Manage Users')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Manage Users</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <form class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}" style="width:220px">
            <select name="role" class="form-select form-select-sm" style="width:140px">
                <option value="">All Roles</option>
                <option value="student" {{ request('role')=='student'?'selected':'' }}>Students</option>
                <option value="instructor" {{ request('role')=='instructor'?'selected':'' }}>Instructors</option>
                <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admins</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Roll No</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>
                    <div class="fw-semibold">{{ $u->name }}</div>
                    <small class="text-muted">{{ $u->institution }}</small>
                </td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->phone ?? '—' }}</td>
                <td><span class="badge bg-{{ $u->role==='admin'?'dark':($u->role==='instructor'?'success':'primary') }}">{{ ucfirst($u->role) }}</span></td>
                <td>{{ $u->roll_number ?? '—' }}</td>
                <td>
                    <span class="badge bg-{{ $u->is_active?'success':'danger' }}">{{ $u->is_active?'Active':'Disabled' }}</span>
                </td>
                <td><small>{{ $u->created_at->format('d M Y') }}</small></td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <form method="POST" action="{{ route('admin.users.toggle',$u) }}">
                            @csrf
                            <button class="btn btn-{{ $u->is_active?'outline-warning':'outline-success' }}" title="{{ $u->is_active?'Disable':'Enable' }}">
                                <i class="bi bi-toggle-{{ $u->is_active?'on':'off' }}"></i>
                            </button>
                        </form>
                        @if($u->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.delete',$u) }}" onsubmit="return confirm('Delete this user permanently?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection