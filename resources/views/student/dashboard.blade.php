{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/student/dashboard.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title','Student Dashboard')
@section('content')
@php
    $user = auth()->user();
    $attempts = \App\Models\TestAttempt::where('user_id',$user->id)->with('test')->latest()->take(5)->get();
    $totalAttempts = $attempts->count();
    $submitted = $attempts->whereIn('status',['submitted','auto_submitted'])->count();
    $avgPct = $attempts->whereNotNull('percentage')->avg('percentage');
@endphp

<div class="bg-success bg-gradient text-white rounded-3 p-4 mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-1">Welcome, {{ $user->name }}! 🎓</h4>
            <p class="mb-0 opacity-75">Roll: {{ $user->roll_number }} | {{ $user->institution ?? 'SWF Portal' }}</p>
        </div>
        <div class="col-auto">
            <div class="bg-white bg-opacity-20 rounded-3 p-3 text-center">
                <div class="fw-bold">Join a Test</div>
                <form class="d-flex gap-2 mt-2" onsubmit="window.location='/test/join/'+document.getElementById('tcode').value;return false;">
                    <input id="tcode" class="form-control form-control-sm" placeholder="Enter Test Code" style="width:130px">
                    <button class="btn btn-warning btn-sm fw-semibold">Go</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    @foreach([
        ['label'=>'Tests Taken','value'=>$totalAttempts,'icon'=>'bi-journal-check','color'=>'primary'],
        ['label'=>'Completed','value'=>$submitted,'icon'=>'bi-check-circle','color'=>'success'],
        ['label'=>'Avg Score','value'=>$avgPct?round($avgPct,1).'%':'N/A','icon'=>'bi-graph-up','color'=>'warning'],
        ['label'=>'Subscription','value'=>$user->activeSubscription?$user->activeSubscription->plan->name:'Free','icon'=>'bi-gem','color'=>'info'],
    ] as $s)
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 d-flex gap-3 align-items-center">
                <div class="bg-{{ $s['color'] }} bg-opacity-10 text-{{ $s['color'] }} rounded-3 p-3 fs-4">
                    <i class="bi {{ $s['icon'] }}"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $s['value'] }}</div>
                    <div class="text-muted small">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-journal-check me-2 text-success"></i>Recent Tests</h6>
        <a href="{{ route('student.my-tests') }}" class="btn btn-sm btn-outline-success">View All</a>
    </div>
    <div class="card-body p-0">
        @if($attempts->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journal-x display-4 text-muted"></i>
            <p class="text-muted mt-3">You haven't taken any tests yet.</p>
            <p class="text-muted small">Enter a test code above to get started!</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Test</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Rank</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Result</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($attempts as $attempt)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($attempt->test->title,35) }}</div>
                        <small class="text-muted">{{ $attempt->test->test_code }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $attempt->status==='submitted'?'success':($attempt->status==='auto_submitted'?'warning':'secondary') }}">
                            {{ ucfirst(str_replace('_',' ',$attempt->status)) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($attempt->obtained_marks !== null)
                        {{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}
                        <div class="small text-muted">{{ $attempt->percentage }}%</div>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($attempt->rank)
                        <span class="badge bg-primary">#{{ $attempt->rank }}</span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center"><small>{{ $attempt->started_at->format('d M Y') }}</small></td>
                    <td class="text-center">
                        <a href="{{ route('student.result',$attempt) }}" class="btn btn-sm btn-outline-primary">View</a>
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