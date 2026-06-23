{{-- FILE PATH: resources/views/student/my-tests.blade.php --}}
@extends('layouts.app')
@section('title', 'My Tests')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-journal-check me-2 text-success"></i>My Tests</h4>
        <small class="text-muted">All your test attempts and results</small>
    </div>
    <div class="bg-white border rounded-3 px-3 py-2 shadow-sm">
        <form class="d-flex gap-2 align-items-center" onsubmit="window.location='/test/join/'+document.getElementById('joinCode').value.trim().toUpperCase();return false;">
            <i class="bi bi-key text-primary"></i>
            <input id="joinCode" class="form-control form-control-sm border-0 shadow-none" placeholder="Enter Test Code" style="width:140px" required>
            <button class="btn btn-primary btn-sm fw-semibold">Join</button>
        </form>
    </div>
</div>

@if($attempts->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-journal-x display-3 text-muted mb-3 d-block"></i>
        <h5 class="fw-bold">No Tests Taken Yet</h5>
        <p class="text-muted">Enter a test code above to join and start your first exam.</p>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Test</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Percentage</th>
                        <th class="text-center">Rank</th>
                        <th class="text-center">Violations</th>
                        <th class="text-center">Time Taken</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($attempts as $i => $attempt)
                @php
                    $pct = $attempt->percentage ?? 0;
                    $color = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                @endphp
                <tr>
                    <td class="text-muted small">{{ ($attempts->currentPage()-1)*15 + $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($attempt->test->title, 40) }}</div>
                        <small class="text-muted">
                            <code>{{ $attempt->test->test_code }}</code>
                            &bull; {{ $attempt->test->duration_minutes }} min
                        </small>
                    </td>
                    <td class="text-center">
                        @php
                            $statusMap = [
                                'submitted'      => ['bg-success', 'Submitted'],
                                'auto_submitted'  => ['bg-warning text-dark', 'Auto-Submitted'],
                                'in_progress'    => ['bg-info text-dark', 'In Progress'],
                            ];
                            [$badgeClass, $label] = $statusMap[$attempt->status] ?? ['bg-secondary', ucfirst($attempt->status)];
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                    </td>
                    <td class="text-center">
                        @if($attempt->obtained_marks !== null)
                            <span class="fw-semibold">{{ $attempt->obtained_marks }}</span>
                            <span class="text-muted">/{{ $attempt->total_marks }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($attempt->percentage !== null)
                            <span class="badge bg-{{ $color }} bg-opacity-15 text-{{ $color }} border border-{{ $color }} fw-semibold">
                                {{ $attempt->percentage }}%
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($attempt->rank)
                            @if($attempt->rank == 1)
                                <span class="fw-bold text-warning">🥇 #1</span>
                            @elseif($attempt->rank == 2)
                                <span class="fw-bold text-secondary">🥈 #2</span>
                            @elseif($attempt->rank == 3)
                                <span class="fw-bold" style="color:#cd7f32">🥉 #3</span>
                            @else
                                <span class="badge bg-primary">#{{ $attempt->rank }}</span>
                            @endif
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($attempt->violation_count > 0)
                            <span class="badge bg-danger">{{ $attempt->violation_count }}</span>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success">0</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <small class="text-muted">
                            {{ $attempt->time_taken_seconds ? gmdate('H:i:s', $attempt->time_taken_seconds) : '—' }}
                        </small>
                    </td>
                    <td class="text-center">
                        <small class="text-muted">{{ $attempt->started_at?->format('d M Y') }}</small>
                    </td>
                    <td class="text-center">
                        @if(in_array($attempt->status, ['submitted','auto_submitted']))
                            <a href="{{ route('student.result', $attempt) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-bar-chart me-1"></i>Result
                            </a>
                        @elseif($attempt->status === 'in_progress')
                            <a href="{{ route('student.test.instructions', $attempt->test) }}" class="btn btn-sm btn-warning fw-semibold">
                                <i class="bi bi-play-circle me-1"></i>Resume
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-3">
        <small class="text-muted">Showing {{ $attempts->firstItem() }}–{{ $attempts->lastItem() }} of {{ $attempts->total() }} attempts</small>
        {{ $attempts->links() }}
    </div>
</div>
@endif

@endsection