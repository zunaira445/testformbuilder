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
<div class="rounded-3 p-3 p-md-4 mb-4 text-white"
     style="background:linear-gradient(135deg,#1e40af,#1d4ed8)">
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
        <div>
            <h5 class="fw-bold mb-1">Welcome back, {{ $user->name }}! 👋</h5>
            <p class="mb-0 opacity-75 small">Manage your tests and track student performance</p>
        </div>
        <a href="{{ route('instructor.tests.create') }}" class="btn btn-warning fw-semibold btn-sm px-3 flex-shrink-0">
            <i class="bi bi-plus-circle me-1"></i>Create Test
        </a>
    </div>
</div>

{{-- Subscription Banner --}}
@if(!$sub)
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4 py-2">
    <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
    <div class="small">You are on the <strong>Free Plan</strong>. <a href="{{ route('pricing') }}" class="fw-semibold">Upgrade now</a> for more tests & features.</div>
</div>
@else
<div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2">
    <i class="bi bi-check-circle-fill flex-shrink-0"></i>
    <div class="small"><strong>{{ $sub->plan->name }} Plan</strong> active — expires {{ $sub->expires_at->format('d M Y') }}</div>
</div>
@endif

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Tests','value'=>$totalTests,'icon'=>'bi-journal-text','color'=>'primary'],
        ['label'=>'Total Attempts','value'=>$totalAttempts,'icon'=>'bi-people','color'=>'success'],
        ['label'=>'Open Tests','value'=>$openTests,'icon'=>'bi-door-open','color'=>'warning'],
        ['label'=>'Plan','value'=>$sub?$sub->plan->name:'Free','icon'=>'bi-gem','color'=>'info'],
    ] as $stat)
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="bg-{{ $stat['color'] }} bg-opacity-10 text-{{ $stat['color'] }} rounded-3 p-2" style="font-size:1.2rem">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </div>
                </div>
                <div class="fw-bold fs-4 lh-1">{{ $stat['value'] }}</div>
                <div class="text-muted small mt-1">{{ $stat['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Recent Tests --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>My Tests</h6>
        <a href="{{ route('instructor.tests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        @if($myTests->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journal-plus display-4 text-muted d-block mb-3"></i>
            <p class="text-muted mb-3">No tests created yet.</p>
            <a href="{{ route('instructor.tests.create') }}" class="btn btn-primary btn-sm px-4">Create First Test</a>
        </div>
        @else

        {{-- MOBILE: Card list view --}}
        <div class="d-block d-md-none">
            @foreach($myTests->take(6) as $test)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold text-truncate">{{ $test->title }}</div>
                        <div class="small text-muted">
                            <code class="me-2">{{ $test->test_code }}</code>{{ $test->duration_minutes }}min
                        </div>
                        <div class="mt-1 d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-{{ $test->is_open ? 'success' : 'secondary' }} small">
                                {{ $test->is_open ? 'Open' : 'Closed' }}
                            </span>
                            <span class="text-muted small">{{ $test->attempts_count }} attempts</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-1 flex-shrink-0">
                        <a href="{{ route('instructor.tests.edit',$test) }}" class="btn btn-outline-primary btn-sm py-0 px-2"><i class="bi bi-pencil"></i></a>
                        <a href="{{ route('instructor.tests.results',$test) }}" class="btn btn-outline-success btn-sm py-0 px-2"><i class="bi bi-bar-chart"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- DESKTOP: Table view --}}
        <div class="d-none d-md-block table-responsive">
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
                        <div class="fw-semibold">{{ Str::limit($test->title,38) }}</div>
                        <small class="text-muted">{{ $test->duration_minutes }} min | Mode {{ $test->mode }}</small>
                    </td>
                    <td><code class="bg-light px-2 py-1 rounded">{{ $test->test_code }}</code></td>
                    <td class="text-center">{{ $test->questions()->count() }}</td>
                    <td class="text-center">{{ $test->attempts_count }}</td>
                    <td class="text-center">
                        <span class="badge bg-{{ $test->is_open ? 'success' : 'secondary' }}">{{ $test->is_open ? 'Open' : 'Closed' }}</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('instructor.tests.edit',$test) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('instructor.tests.results',$test) }}" class="btn btn-outline-success"><i class="bi bi-bar-chart"></i></a>
                            <form method="POST" action="{{ route('instructor.tests.toggle-open',$test) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-{{ $test->is_open ? 'warning' : 'secondary' }}">
                                    <i class="bi bi-{{ $test->is_open ? 'lock' : 'unlock' }}"></i>
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