{{-- FILE PATH: resources/views/admin/tests/index.blade.php --}}
@extends('layouts.app')
@section('title', 'All Tests')
@section('content')

@php
    $tests = \App\Models\Test::with(['user','category'])->withCount('attempts')->latest()->paginate(20);
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>All Tests</h4>
    <span class="badge bg-primary fs-6 px-3 py-2">{{ $tests->total() }} Tests</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Test Title</th>
                    <th>Instructor</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Questions</th>
                    <th class="text-center">Attempts</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Created</th>
                </tr>
            </thead>
            <tbody>
            @forelse($tests as $test)
            <tr>
                <td>{{ $test->id }}</td>
                <td>
                    <div class="fw-semibold">{{ Str::limit($test->title, 40) }}</div>
                    <small class="text-muted">{{ $test->duration_minutes }} min &bull; Mode {{ $test->mode }}</small>
                </td>
                <td>
                    <div>{{ $test->user->name }}</div>
                    <small class="text-muted">{{ $test->user->email }}</small>
                </td>
                <td class="text-center"><code class="bg-light px-2 py-1 rounded">{{ $test->test_code }}</code></td>
                <td class="text-center">{{ $test->questions()->count() }}</td>
                <td class="text-center">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">{{ $test->attempts_count }}</span>
                </td>
                <td class="text-center">
                    @if($test->is_open)
                    <span class="badge bg-success">Open</span>
                    @else
                    <span class="badge bg-secondary">Closed</span>
                    @endif
                </td>
                <td class="text-center"><small class="text-muted">{{ $test->created_at->format('d M Y') }}</small></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">No tests found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent">{{ $tests->links() }}</div>
</div>

@endsection