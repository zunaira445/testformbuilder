{{-- FILE PATH: resources/views/admin/settings.blade.php --}}
@extends('layouts.app')
@section('title', 'Site Settings')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-primary"></i>Site Settings</h4>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    {{-- Site Info --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-globe me-2 text-primary"></i>Platform Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Platform Name</label>
                    <input type="text" class="form-control bg-light" value="SWF Portal" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Organization</label>
                    <input type="text" class="form-control bg-light" value="Student Welfare Foundation" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact Email</label>
                    <input type="email" class="form-control bg-light" value="swfhelpers@gmail.com" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">WhatsApp</label>
                    <input type="text" class="form-control bg-light" value="+92 314 8379859" disabled>
                </div>
                <div class="alert alert-info small mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    To update platform details, modify the <code>.env</code> file or contact the developer.
                </div>
            </div>
        </div>
    </div>

    {{-- System Stats --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-success"></i>System Statistics</h6>
            </div>
            <div class="card-body">
                @php
                    $stats = [
                        ['label' => 'Total Users',           'val' => \App\Models\User::count()],
                        ['label' => 'Total Tests',           'val' => \App\Models\Test::count()],
                        ['label' => 'Total Attempts',        'val' => \App\Models\TestAttempt::count()],
                        ['label' => 'Active Subscriptions',  'val' => \App\Models\UserSubscription::where('is_active',true)->count()],
                        ['label' => 'Pending Payments',      'val' => \App\Models\Payment::where('status','pending')->count()],
                        ['label' => 'Total Revenue (PKR)',   'val' => 'PKR '.number_format(\App\Models\Payment::where('status','approved')->sum('amount'))],
                    ];
                @endphp
                @foreach($stats as $s)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">{{ $s['label'] }}</span>
                    <strong>{{ $s['val'] }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Admin Account --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-warning"></i>Admin Accounts</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Name</th><th>Email</th><th>Joined</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\User::where('role','admin')->get() as $admin)
                    <tr>
                        <td>{{ $admin->id }}</td>
                        <td class="fw-semibold">{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td><small class="text-muted">{{ $admin->created_at->format('d M Y') }}</small></td>
                        <td><span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">{{ $admin->is_active ? 'Active' : 'Disabled' }}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection