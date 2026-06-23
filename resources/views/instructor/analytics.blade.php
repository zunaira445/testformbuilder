{{-- FILE PATH: resources/views/instructor/analytics.blade.php --}}
@extends('layouts.app')
@section('title', 'Analytics')
@section('content')

@php
    $user = auth()->user();
    $tests = \App\Models\Test::where('user_id', $user->id)->withCount('attempts')->get();
    $totalTests    = $tests->count();
    $totalAttempts = \App\Models\TestAttempt::whereIn('test_id', $tests->pluck('id'))
                        ->whereIn('status',['submitted','auto_submitted'])->count();
    $avgScore = \App\Models\TestAttempt::whereIn('test_id', $tests->pluck('id'))
                    ->whereIn('status',['submitted','auto_submitted'])
                    ->avg('percentage');
    $violations = \App\Models\TestAttempt::whereIn('test_id', $tests->pluck('id'))->sum('violation_count');
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Analytics</h4>
        <small class="text-muted">Performance overview across all your tests</small>
    </div>
</div>

{{-- Summary Stats --}}
<div class="row g-4 mb-4">
    @foreach([
        ['label'=>'Total Tests','val'=>$totalTests,'icon'=>'bi-journal-text','color'=>'primary'],
        ['label'=>'Total Submissions','val'=>$totalAttempts,'icon'=>'bi-people','color'=>'success'],
        ['label'=>'Average Score','val'=>$avgScore ? round($avgScore,1).'%' : 'N/A','icon'=>'bi-graph-up','color'=>'warning'],
        ['label'=>'Total Violations','val'=>$violations,'icon'=>'bi-exclamation-triangle','color'=>'danger'],
    ] as $s)
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-{{ $s['color'] }} bg-opacity-10 text-{{ $s['color'] }} rounded-3 p-3 fs-4">
                        <i class="bi {{ $s['icon'] }}"></i>
                    </div>
                </div>
                <div class="fw-bold fs-3">{{ $s['val'] }}</div>
                <div class="text-muted small">{{ $s['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Per-Test Analytics Table --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-table me-2 text-primary"></i>Test Performance Breakdown</h6>
    </div>
    @if($tests->isEmpty())
    <div class="card-body text-center py-5">
        <i class="bi bi-bar-chart display-4 text-muted mb-3 d-block"></i>
        <p class="text-muted">Create tests to see analytics here.</p>
        <a href="{{ route('instructor.tests.create') }}" class="btn btn-primary btn-sm">Create First Test</a>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Test Title</th>
                    <th class="text-center">Questions</th>
                    <th class="text-center">Submissions</th>
                    <th class="text-center">Avg Score</th>
                    <th class="text-center">Highest</th>
                    <th class="text-center">Lowest</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($tests as $test)
            @php
                $testAttempts = \App\Models\TestAttempt::where('test_id',$test->id)
                    ->whereIn('status',['submitted','auto_submitted']);
                $avg  = $testAttempts->avg('percentage');
                $high = $testAttempts->max('percentage');
                $low  = $testAttempts->min('percentage');
            @endphp
            <tr>
                <td>
                    <div class="fw-semibold">{{ Str::limit($test->title, 40) }}</div>
                    <small class="text-muted"><code>{{ $test->test_code }}</code> &bull; {{ $test->duration_minutes }} min</small>
                </td>
                <td class="text-center">{{ $test->questions()->count() }}</td>
                <td class="text-center">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">{{ $test->attempts_count }}</span>
                </td>
                <td class="text-center">
                    @if($avg)
                    <span class="fw-semibold text-{{ $avg >= 70 ? 'success' : ($avg >= 50 ? 'warning' : 'danger') }}">
                        {{ round($avg,1) }}%
                    </span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="text-success fw-semibold">{{ $high ? round($high,1).'%' : '—' }}</span>
                </td>
                <td class="text-center">
                    <span class="text-danger fw-semibold">{{ $low ? round($low,1).'%' : '—' }}</span>
                </td>
                <td class="text-center">
                    @if($test->is_open)
                    <span class="badge bg-success">Open</span>
                    @else
                    <span class="badge bg-secondary">Closed</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('instructor.tests.results', $test) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-bar-chart me-1"></i>Results
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Recent Activity --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-activity me-2 text-success"></i>Recent Submissions</h6>
    </div>
    <div class="card-body p-0">
        @php
            $recentAttempts = \App\Models\TestAttempt::whereIn('test_id', $tests->pluck('id'))
                ->with(['user','test'])
                ->whereIn('status',['submitted','auto_submitted'])
                ->latest('submitted_at')
                ->take(10)
                ->get();
        @endphp
        @if($recentAttempts->isEmpty())
        <div class="text-center py-4 text-muted small">No submissions yet.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Test</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($recentAttempts as $a)
                <tr>
                    <td>{{ $a->user->name }}</td>
                    <td>{{ Str::limit($a->test->title,30) }}</td>
                    <td class="text-center">
                        <span class="fw-semibold">{{ $a->obtained_marks }}/{{ $a->total_marks }}</span>
                        <span class="text-muted">({{ $a->percentage }}%)</span>
                    </td>
                    <td class="text-center text-muted">{{ $a->submitted_at?->diffForHumans() }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection