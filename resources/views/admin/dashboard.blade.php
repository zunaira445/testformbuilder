{{-- FILE PATH: resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Admin Dashboard')
@section('content')

<div class="rounded-3 p-3 p-md-4 mb-4 text-white" style="background:linear-gradient(135deg,#1e3a8a,#1e40af)">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h5>
            <p class="mb-0 opacity-75 small">SWF Portal — System Overview</p>
        </div>
        <span class="badge bg-white text-primary small d-none d-sm-inline">{{ now()->format('d M Y') }}</span>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Users','val'=>$stats['total_users'],'icon'=>'bi-people-fill','color'=>'primary'],
        ['label'=>'Students','val'=>$stats['students'],'icon'=>'bi-mortarboard-fill','color'=>'success'],
        ['label'=>'Instructors','val'=>$stats['instructors'],'icon'=>'bi-person-workspace','color'=>'info'],
        ['label'=>'Total Tests','val'=>$stats['total_tests'],'icon'=>'bi-journal-text','color'=>'warning'],
        ['label'=>'Total Attempts','val'=>$stats['total_attempts'],'icon'=>'bi-clipboard-check','color'=>'secondary'],
        ['label'=>'Pending Payments','val'=>$stats['pending_payments'],'icon'=>'bi-credit-card','color'=>'danger'],
        ['label'=>'Revenue (PKR)','val'=>number_format($stats['revenue']),'icon'=>'bi-cash-coin','color'=>'success'],
        ['label'=>'Active Subscriptions','val'=>$stats['active_subs'],'icon'=>'bi-gem','color'=>'primary'],
    ] as $s)
    <div class="col-6 col-sm-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="bg-{{ $s['color'] }} bg-opacity-10 text-{{ $s['color'] }} rounded-3 p-2 mb-2 d-inline-flex" style="font-size:1.1rem">
                    <i class="bi {{ $s['icon'] }}"></i>
                </div>
                <div class="fw-bold fs-5 lh-1">{{ $s['val'] }}</div>
                <div class="text-muted small mt-1">{{ $s['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Recent Users --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Recent Users</h6>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">

                {{-- Mobile --}}
                <div class="d-block d-md-none">
                    @foreach($recentUsers as $u)
                    <div class="border-bottom p-3 d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-{{ $u->role==='admin'?'dark':($u->role==='instructor'?'success':'primary') }} text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                             style="width:36px;height:36px;font-size:.8rem">
                            {{ strtoupper(substr($u->name,0,1)) }}
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-truncate small">{{ $u->name }}</div>
                            <div class="text-muted" style="font-size:.72rem">{{ $u->email }}</div>
                        </div>
                        <span class="badge bg-{{ $u->role==='admin'?'dark':($u->role==='instructor'?'success':'primary') }} small">{{ ucfirst($u->role) }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Desktop --}}
                <div class="d-none d-md-block table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr><th>Name</th><th>Role</th><th>Joined</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        @foreach($recentUsers as $u)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $u->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $u->email }}</div>
                            </td>
                            <td><span class="badge bg-{{ $u->role==='admin'?'dark':($u->role==='instructor'?'success':'primary') }}">{{ ucfirst($u->role) }}</span></td>
                            <td class="text-muted">{{ $u->created_at->format('d M Y') }}</td>
                            <td><span class="badge bg-{{ $u->is_active?'success':'danger' }}">{{ $u->is_active?'Active':'Disabled' }}</span></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-credit-card me-2 text-success"></i>Recent Payments</h6>
                <a href="{{ route('admin.payments') }}" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body p-0">

                {{-- Mobile --}}
                <div class="d-block d-md-none">
                    @foreach($recentPayments as $p)
                    <div class="border-bottom p-3 d-flex align-items-center gap-2">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-truncate small">{{ $p->user->name }}</div>
                            <div class="text-muted" style="font-size:.72rem">{{ $p->plan->name }} &bull; PKR {{ number_format($p->amount) }}</div>
                        </div>
                        <span class="badge bg-{{ $p->status==='approved'?'success':($p->status==='rejected'?'danger':'warning text-dark') }} small">
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>

                {{-- Desktop --}}
                <div class="d-none d-md-block table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr><th>User</th><th>Plan</th><th>Amount</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        @foreach($recentPayments as $p)
                        <tr>
                            <td>{{ $p->user->name }}</td>
                            <td><span class="badge bg-primary">{{ $p->plan->name }}</span></td>
                            <td class="fw-semibold">PKR {{ number_format($p->amount) }}</td>
                            <td>
                                <span class="badge bg-{{ $p->status==='approved'?'success':($p->status==='rejected'?'danger':'warning text-dark') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection