{{-- FILE PATH: resources/views/instructor/tests/index.blade.php --}}
@extends('layouts.app')
@section('title', 'My Tests')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>My Tests</h4>
        <small class="text-muted">Manage all your created tests</small>
    </div>
    <a href="{{ route('instructor.tests.create') }}" class="btn btn-primary fw-semibold">
        <i class="bi bi-plus-circle me-2"></i>Create New Test
    </a>
</div>

@if($tests->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-journal-plus display-3 text-muted mb-3 d-block"></i>
        <h5 class="fw-bold">No Tests Yet</h5>
        <p class="text-muted">You haven't created any tests. Start building your first exam!</p>
        <a href="{{ route('instructor.tests.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-circle me-2"></i>Create First Test
        </a>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Test</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Questions</th>
                        <th class="text-center">Attempts</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Results</th>
                        <th class="text-center">Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tests as $test)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($test->title, 45) }}</div>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>{{ $test->duration_minutes }} min
                            &bull; Mode {{ $test->mode }}
                            @if($test->negative_marking) &bull; <span class="text-danger">-{{ $test->negative_marks }}</span>@endif
                        </small>
                    </td>
                    <td class="text-center">
                        <code class="bg-light px-2 py-1 rounded small">{{ $test->test_code }}</code>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold">{{ $test->questions()->count() }}</span>
                        <div class="small text-muted">{{ $test->total_marks }} marks</div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1">
                            {{ $test->attempts_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($test->is_open)
                            <span class="badge bg-success"><i class="bi bi-unlock me-1"></i>Open</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-lock me-1"></i>Closed</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($test->result_published)
                            <span class="badge bg-info"><i class="bi bi-check-circle me-1"></i>Published</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass me-1"></i>Pending</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <small class="text-muted">{{ $test->created_at->format('d M Y') }}</small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('instructor.tests.edit', $test) }}"
                               class="btn btn-outline-primary" title="Edit Test">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('instructor.tests.results', $test) }}"
                               class="btn btn-outline-success" title="View Results">
                                <i class="bi bi-bar-chart"></i>
                            </a>
                            <form method="POST" action="{{ route('instructor.tests.toggle-open', $test) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-{{ $test->is_open ? 'warning' : 'secondary' }}"
                                        title="{{ $test->is_open ? 'Close Test' : 'Open Test' }}">
                                    <i class="bi bi-{{ $test->is_open ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                            <button class="btn btn-outline-info"
                                    onclick="navigator.clipboard.writeText('{{ $test->share_link }}');this.innerHTML='<i class=\'bi bi-check\'></i>';setTimeout(()=>this.innerHTML='<i class=\'bi bi-share\'></i>',2000)"
                                    title="Copy Share Link">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        {{ $tests->links() }}
    </div>
</div>
@endif

@endsection