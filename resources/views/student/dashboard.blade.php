{{-- FILE PATH: resources/views/student/dashboard.blade.php --}}
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

{{-- Welcome Banner --}}
<div class="rounded-3 p-3 p-md-4 mb-4 text-white"
     style="background:linear-gradient(135deg,#059669,#047857)">
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
        <div>
            <h5 class="fw-bold mb-1">Welcome, {{ $user->name }}! 🎓</h5>
            <p class="mb-0 opacity-75 small">
                Roll: {{ $user->roll_number ?? 'N/A' }} | {{ $user->institution ?? 'SWF Portal' }}
            </p>
        </div>
        {{-- Join Test inline --}}
        <form class="d-flex gap-2 flex-shrink-0"
              onsubmit="window.location='/test/join/'+document.getElementById('heroCode').value.trim().toUpperCase();return false;">
            <input id="heroCode" class="form-control form-control-sm"
                   placeholder="Test Code" style="width:120px;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);color:#fff"
                   oninput="this.value=this.value.toUpperCase()">
            <button class="btn btn-warning btn-sm fw-semibold">Go</button>
        </form>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Tests Taken','value'=>$totalAttempts,'icon'=>'bi-journal-check','color'=>'primary'],
        ['label'=>'Completed','value'=>$submitted,'icon'=>'bi-check-circle','color'=>'success'],
        ['label'=>'Avg Score','value'=>$avgPct?round($avgPct,1).'%':'N/A','icon'=>'bi-graph-up','color'=>'warning'],
        ['label'=>'Plan','value'=>$user->activeSubscription?$user->activeSubscription->plan->name:'Free','icon'=>'bi-gem','color'=>'info'],
    ] as $s)
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="bg-{{ $s['color'] }} bg-opacity-10 text-{{ $s['color'] }} rounded-3 p-2 mb-2 d-inline-flex" style="font-size:1.2rem">
                    <i class="bi {{ $s['icon'] }}"></i>
                </div>
                <div class="fw-bold fs-4 lh-1">{{ $s['value'] }}</div>
                <div class="text-muted small mt-1">{{ $s['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Recent Tests --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-journal-check me-2 text-success"></i>Recent Tests</h6>
        <a href="{{ route('student.my-tests') }}" class="btn btn-sm btn-outline-success">View All</a>
    </div>
    <div class="card-body p-0">
        @if($attempts->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journal-x display-4 text-muted d-block mb-3"></i>
            <p class="text-muted mb-2">No tests taken yet.</p>
            <p class="text-muted small">Enter a test code above to get started!</p>
        </div>
        @else

        {{-- MOBILE: Card view --}}
        <div class="d-block d-md-none">
            @foreach($attempts as $attempt)
            @php $pct = $attempt->percentage ?? 0; @endphp
            <div class="border-bottom p-3 d-flex align-items-center gap-3">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-semibold text-truncate small">{{ $attempt->test->title }}</div>
                    <div class="small text-muted">{{ $attempt->test->test_code }}</div>
                    <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                        <span class="badge bg-{{ $attempt->status==='submitted'?'success':($attempt->status==='auto_submitted'?'warning':'secondary') }} small">
                            {{ $attempt->status === 'auto_submitted' ? 'Auto' : ucfirst($attempt->status) }}
                        </span>
                        @if($attempt->percentage !== null)
                        <span class="small fw-semibold text-{{ $pct>=70?'success':($pct>=50?'warning':'danger') }}">{{ $pct }}%</span>
                        @endif
                        @if($attempt->rank)
                        <span class="badge bg-primary small">#{{ $attempt->rank }}</span>
                        @endif
                    </div>
                </div>
                @if(in_array($attempt->status,['submitted','auto_submitted']))
                <a href="{{ route('student.result',$attempt) }}" class="btn btn-sm btn-outline-primary flex-shrink-0">Result</a>
                @endif
            </div>
            @endforeach
        </div>

        {{-- DESKTOP: Table view --}}
        <div class="d-none d-md-block table-responsive">
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
                        <div class="fw-semibold">{{ Str::limit($attempt->test->title,32) }}</div>
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
                        @else <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($attempt->rank) <span class="badge bg-primary">#{{ $attempt->rank }}</span>
                        @else <span class="text-muted">—</span>
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