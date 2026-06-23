{{-- FILE PATH: resources/views/instructor/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Instructor Dashboard')
@section('content')

@php
    $user = auth()->user();
    $myTests = \App\Models\Test::where('user_id',$user->id)->withCount(['attempts','sections'])->latest()->get();
    $totalTests    = $myTests->count();
    $totalAttempts = $myTests->sum('attempts_count');
    $openTests     = $myTests->where('is_open',true)->count();
    $sub = $user->activeSubscription;
@endphp

{{-- Welcome Banner --}}
<div class="bg-primary bg-gradient text-white rounded-3 p-4 mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-1">Welcome back, {{ $user->name }}! 👋</h4>
            <p class="mb-0 opacity-75">Manage your tests and track student performance</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('instructor.tests.create') }}" class="btn btn-warning fw-semibold">
                <i class="bi bi-plus-circle me-2"></i>Create New Test
            </a>
        </div>
    </div>
</div>

{{-- Subscription Warning --}}
@if(!$sub)
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div>You are on the <strong>Free Plan</strong>. <a href="{{ route('pricing') }}" class="fw-semibold">Upgrade now</a> for more tests, students & features.</div>
</div>
@else
<div class="alert alert-success d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-check-circle-fill fs-5"></i>
    <div><strong>{{ $sub->plan->name }} Plan</strong> active — expires {{ $sub->expires_at->format('d M Y') }}</div>
</div>
@endif

{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    @foreach([
        ['label'=>'Total Tests','value'=>$totalTests,'icon'=>'bi-journal-text','color'=>'primary','bg'=>'primary'],
        ['label'=>'Total Attempts','value'=>$totalAttempts,'icon'=>'bi-people','color'=>'success','bg'=>'success'],
        ['label'=>'Open Tests','value'=>$openTests,'icon'=>'bi-door-open','color'=>'warning','bg'=>'warning'],
        ['label'=>'Subscription','value'=>$sub?$sub->plan->name:'Free','icon'=>'bi-gem','color'=>'info','bg'=>'info'],
    ] as $stat)
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-{{ $stat['bg'] }} bg-opacity-10 text-{{ $stat['color'] }}" style="width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </div>
                </div>
                <div class="fw-bold fs-3">{{ $stat['value'] }}</div>
                <div class="text-muted small">{{ $stat['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Recent Tests Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>My Tests</h6>
        <a href="{{ route('instructor.tests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        @if($myTests->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journal-plus display-4 text-muted"></i>
            <p class="text-muted mt-3">No tests created yet.</p>
            <a href="{{ route('instructor.tests.create') }}" class="btn btn-primary">Create First Test</a>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Test Title</th>
                        <th>Code</th>
                        <th class="text-center">Questions</th>
                        <th class="text-center">Attempts</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($myTests->take(8) as $test)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($test->title,40) }}</div>
                        <small class="text-muted">{{ $test->duration_minutes }} min | Mode {{ $test->mode }}</small>
                    </td>
                    <td><code class="bg-light px-2 py-1 rounded">{{ $test->test_code }}</code></td>
                    <td class="text-center">{{ $test->questions()->count() }}</td>
                    <td class="text-center">{{ $test->attempts_count }}</td>
                    <td class="text-center">
                        @if($test->is_open)
                            <span class="badge bg-success">Open</span>
                        @else
                            <span class="badge bg-secondary">Closed</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('instructor.tests.edit',$test) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('instructor.tests.results',$test) }}" class="btn btn-outline-success" title="Results"><i class="bi bi-bar-chart"></i></a>
                            <form method="POST" action="{{ route('instructor.tests.toggle-open',$test) }}" class="d-inline">
                                @csrf
                                <button class="btn {{ $test->is_open ? 'btn-outline-warning' : 'btn-outline-secondary' }}" title="{{ $test->is_open?'Close':'Open' }}">
                                    <i class="bi {{ $test->is_open ? 'bi-lock' : 'bi-unlock' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection