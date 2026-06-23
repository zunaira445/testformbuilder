{{-- FILE PATH: resources/views/instructor/tests/results.blade.php --}}
@extends('layouts.app')
@section('title', 'Test Results — ' . $test->title)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('instructor.tests.edit', $test) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">{{ $test->title }}</h4>
            <small class="text-muted">Results &amp; Rankings &mdash; Code: <code>{{ $test->test_code }}</code></small>
        </div>
    </div>
    <div class="d-flex gap-2">
        @if(!$test->result_published)
        <form method="POST" action="{{ route('instructor.tests.publish', $test) }}">
            @csrf
            <button class="btn btn-success fw-semibold">
                <i class="bi bi-trophy me-1"></i>Publish Results &amp; Assign Ranks
            </button>
        </form>
        @else
        <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check-circle me-1"></i>Results Published</span>
        @endif
    </div>
</div>

{{-- Summary Cards --}}
@php
    $totalAttempts = $attempts->total();
    $avgPct = $attempts->avg('percentage');
    $topScore = $attempts->max('percentage');
@endphp
<div class="row g-3 mb-4">
    @foreach([
        ['label' => 'Total Attempts', 'val' => $totalAttempts, 'icon' => 'bi-people', 'color' => 'primary'],
        ['label' => 'Average Score', 'val' => $avgPct ? round($avgPct, 1).'%' : 'N/A', 'icon' => 'bi-graph-up', 'color' => 'success'],
        ['label' => 'Top Score', 'val' => $topScore ? round($topScore, 1).'%' : 'N/A', 'icon' => 'bi-trophy', 'color' => 'warning'],
        ['label' => 'Total Marks', 'val' => $test->total_marks, 'icon' => 'bi-clipboard-check', 'color' => 'info'],
    ] as $s)
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 d-flex gap-3 align-items-center">
                <div class="bg-{{ $s['color'] }} bg-opacity-10 text-{{ $s['color'] }} rounded-3 p-2 fs-4">
                    <i class="bi {{ $s['icon'] }}"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $s['val'] }}</div>
                    <div class="text-muted small">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Results Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ol me-2 text-primary"></i>Student Rankings</h6>
        @if($test->result_published)
        <span class="badge bg-success">Results are visible to students</span>
        @else
        <span class="badge bg-warning text-dark">Results hidden from students</span>
        @endif
    </div>

    @if($attempts->isEmpty())
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox display-3 text-muted mb-3 d-block"></i>
        <h5 class="text-muted">No Submissions Yet</h5>
        <p class="text-muted small">Students haven't submitted this test yet.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width:60px">Rank</th>
                    <th>Student</th>
                    <th class="text-center">Marks</th>
                    <th class="text-center">Percentage</th>
                    <th class="text-center">Time Taken</th>
                    <th class="text-center">Violations</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Submitted</th>
                </tr>
            </thead>
            <tbody>
            @foreach($attempts as $attempt)
            @php
                $pct = $attempt->percentage ?? 0;
                $color = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
            @endphp
            <tr>
                <td class="text-center">
                    @if($attempt->rank)
                        @if($attempt->rank == 1)
                            <span class="badge bg-warning text-dark fs-6">🥇 1</span>
                        @elseif($attempt->rank == 2)
                            <span class="badge bg-secondary fs-6">🥈 2</span>
                        @elseif($attempt->rank == 3)
                            <span class="badge" style="background:#cd7f32;color:#fff;font-size:.9rem">🥉 3</span>
                        @else
                            <span class="fw-bold text-muted">#{{ $attempt->rank }}</span>
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <div class="fw-semibold">{{ $attempt->user->name }}</div>
                    <small class="text-muted">
                        {{ $attempt->user->email }}
                        @if($attempt->user->roll_number)
                        &bull; Roll: {{ $attempt->user->roll_number }}
                        @endif
                    </small>
                </td>
                <td class="text-center">
                    <span class="fw-semibold">{{ $attempt->obtained_marks }}</span>
                    <span class="text-muted">/{{ $attempt->total_marks }}</span>
                </td>
                <td class="text-center">
                    <div class="d-flex align-items-center gap-2 justify-content-center">
                        <div class="progress" style="width:60px;height:8px;">
                            <div class="progress-bar bg-{{ $color }}" style="width:{{ $pct }}%"></div>
                        </div>
                        <span class="fw-semibold text-{{ $color }} small">{{ $pct }}%</span>
                    </div>
                </td>
                <td class="text-center">
                    <small>{{ $attempt->time_taken_seconds ? gmdate('H:i:s', $attempt->time_taken_seconds) : '—' }}</small>
                </td>
                <td class="text-center">
                    @if($attempt->violation_count > 0)
                    <span class="badge bg-danger">{{ $attempt->violation_count }}</span>
                    @else
                    <span class="badge bg-success">0</span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge bg-{{ $attempt->status === 'submitted' ? 'success' : 'warning' }}">
                        {{ $attempt->status === 'submitted' ? 'Manual' : 'Auto' }}
                    </span>
                </td>
                <td class="text-center">
                    <small class="text-muted">{{ $attempt->submitted_at?->format('d M, H:i') }}</small>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent">
        {{ $attempts->links() }}
    </div>
    @endif
</div>

@endsection