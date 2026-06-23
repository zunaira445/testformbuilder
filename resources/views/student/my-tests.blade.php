{{-- FILE PATH: resources/views/student/my-tests.blade.php --}}
@extends('layouts.app')
@section('title','My Tests')
@section('content')

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-journal-check me-2 text-success"></i>My Tests</h5>
        <small class="text-muted">All your test attempts and results</small>
    </div>
    <div class="w-100" style="max-width:280px">
        <form class="d-flex gap-2"
              onsubmit="window.location='/test/join/'+document.getElementById('joinCode').value.trim().toUpperCase();return false;">
            <input id="joinCode" class="form-control form-control-sm" placeholder="Enter Test Code" required>
            <button class="btn btn-primary btn-sm fw-semibold px-3">Join</button>
        </form>
    </div>
</div>

@if($attempts->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-journal-x display-3 text-muted d-block mb-3"></i>
        <h5 class="fw-bold">No Tests Taken Yet</h5>
        <p class="text-muted small">Enter a test code above to join your first exam.</p>
    </div>
</div>
@else

{{-- MOBILE: Card list --}}
<div class="d-block d-lg-none">
    @foreach($attempts as $attempt)
    @php
        $pct = $attempt->percentage ?? 0;
        $color = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
    @endphp
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <div class="overflow-hidden">
                    <div class="fw-semibold text-truncate">{{ $attempt->test->title }}</div>
                    <small class="text-muted"><code>{{ $attempt->test->test_code }}</code> &bull; {{ $attempt->test->duration_minutes }}min</small>
                </div>
                <span class="badge bg-{{ $attempt->status==='submitted'?'success':($attempt->status==='auto_submitted'?'warning text-dark':'secondary') }} flex-shrink-0">
                    {{ $attempt->status === 'auto_submitted' ? 'Auto' : ucfirst($attempt->status) }}
                </span>
            </div>

            <div class="row g-2 small mb-2">
                <div class="col-6">
                    <div class="bg-light rounded p-2 text-center">
                        <div class="text-muted">Score</div>
                        <div class="fw-bold">
                            {{ $attempt->obtained_marks !== null ? $attempt->obtained_marks.'/'.$attempt->total_marks : '—' }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light rounded p-2 text-center">
                        <div class="text-muted">Percentage</div>
                        <div class="fw-bold text-{{ $attempt->percentage !== null ? $color : 'muted' }}">
                            {{ $attempt->percentage !== null ? $attempt->percentage.'%' : '—' }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light rounded p-2 text-center">
                        <div class="text-muted">Rank</div>
                        <div class="fw-bold">{{ $attempt->rank ? '#'.$attempt->rank : '—' }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light rounded p-2 text-center">
                        <div class="text-muted">Date</div>
                        <div class="fw-bold">{{ $attempt->started_at?->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            @if(in_array($attempt->status,['submitted','auto_submitted']))
            <a href="{{ route('student.result',$attempt) }}" class="btn btn-outline-primary btn-sm w-100">
                <i class="bi bi-bar-chart me-1"></i>View Result
            </a>
            @elseif($attempt->status === 'in_progress')
            <a href="{{ route('student.test.instructions',$attempt->test) }}" class="btn btn-warning btn-sm w-100 fw-semibold">
                <i class="bi bi-play-circle me-1"></i>Resume Test
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- DESKTOP: Table --}}
<div class="card border-0 shadow-sm d-none d-lg-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Test</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Score</th>
                    <th class="text-center">%</th>
                    <th class="text-center">Rank</th>
                    <th class="text-center">Violations</th>
                    <th class="text-center">Time</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($attempts as $i => $attempt)
            @php $pct = $attempt->percentage ?? 0; $color = $pct>=80?'success':($pct>=50?'warning':'danger'); @endphp
            <tr>
                <td class="text-muted small">{{ ($attempts->currentPage()-1)*15 + $i + 1 }}</td>
                <td>
                    <div class="fw-semibold">{{ Str::limit($attempt->test->title,35) }}</div>
                    <small class="text-muted"><code>{{ $attempt->test->test_code }}</code></small>
                </td>
                <td class="text-center">
                    <span class="badge bg-{{ $attempt->status==='submitted'?'success':($attempt->status==='auto_submitted'?'warning text-dark':'secondary') }}">
                        {{ ucfirst(str_replace('_',' ',$attempt->status)) }}
                    </span>
                </td>
                <td class="text-center">
                    @if($attempt->obtained_marks !== null)
                    <span class="fw-semibold">{{ $attempt->obtained_marks }}</span><span class="text-muted">/{{ $attempt->total_marks }}</span>
                    @else <span class="text-muted">—</span> @endif
                </td>
                <td class="text-center">
                    @if($attempt->percentage !== null)
                    <span class="badge bg-{{ $color }} bg-opacity-15 text-{{ $color }} border border-{{ $color }} fw-semibold">{{ $attempt->percentage }}%</span>
                    @else <span class="text-muted">—</span> @endif
                </td>
                <td class="text-center">
                    @if($attempt->rank) <span class="badge bg-primary">#{{ $attempt->rank }}</span>
                    @else <span class="text-muted">—</span> @endif
                </td>
                <td class="text-center">
                    <span class="badge bg-{{ $attempt->violation_count > 0 ? 'danger' : 'success bg-opacity-15 text-success' }}">
                        {{ $attempt->violation_count }}
                    </span>
                </td>
                <td class="text-center"><small>{{ $attempt->time_taken_seconds ? gmdate('H:i:s',$attempt->time_taken_seconds) : '—' }}</small></td>
                <td class="text-center"><small class="text-muted">{{ $attempt->started_at?->format('d M Y') }}</small></td>
                <td class="text-center">
                    @if(in_array($attempt->status,['submitted','auto_submitted']))
                    <a href="{{ route('student.result',$attempt) }}" class="btn btn-sm btn-outline-primary">Result</a>
                    @elseif($attempt->status==='in_progress')
                    <a href="{{ route('student.test.instructions',$attempt->test) }}" class="btn btn-sm btn-warning fw-semibold">Resume</a>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 py-3">
        <small class="text-muted">Showing {{ $attempts->firstItem() }}–{{ $attempts->lastItem() }} of {{ $attempts->total() }}</small>
        {{ $attempts->links() }}
    </div>
</div>

{{-- Mobile pagination --}}
<div class="d-block d-lg-none mt-3 text-center">{{ $attempts->links() }}</div>

@endif

@endsection