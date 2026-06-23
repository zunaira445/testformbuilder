{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/student/test-instructions.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title','Test Instructions')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="fw-bold mb-1">{{ $test->title }}</h5>
                <small class="opacity-75">Code: {{ $test->test_code }} | Duration: {{ $test->duration_minutes }} minutes</small>
            </div>
            <div class="card-body p-4">
                @if($test->description)
                <div class="alert alert-info"><strong>About this test:</strong> {{ $test->description }}</div>
                @endif

                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Test Details</h6>
                <div class="row g-2 mb-4 small">
                    @foreach([
                        ['label'=>'Total Questions','val'=>$test->questions()->where('is_active',true)->count()],
                        ['label'=>'Duration','val'=>$test->duration_minutes.' minutes'],
                        ['label'=>'Total Marks','val'=>$test->total_marks],
                        ['label'=>'Mode','val'=>$test->mode === 'A' ? 'One question at a time' : 'All questions visible'],
                        ['label'=>'Negative Marking','val'=>$test->negative_marking ? 'Yes (-'.$test->negative_marks.' per wrong)' : 'No'],
                        ['label'=>'Anti-Cheat','val'=>$test->anti_cheat ? 'Enabled' : 'Disabled'],
                    ] as $d)
                    <div class="col-6">
                        <div class="bg-light rounded p-2">
                            <div class="text-muted">{{ $d['label'] }}</div>
                            <div class="fw-semibold">{{ $d['val'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($test->instructions)
                <h6 class="fw-bold text-warning mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Instructions</h6>
                <div class="bg-warning bg-opacity-10 border border-warning rounded-3 p-3 mb-4">
                    {!! nl2br(e($test->instructions)) !!}
                </div>
                @endif

                @if($test->anti_cheat)
                <div class="alert alert-danger small mb-4">
                    <strong><i class="bi bi-shield-exclamation me-2"></i>Anti-Cheat Warning:</strong>
                    Tab switching, window minimizing, and copy-paste are monitored. You will receive warnings and your test will be auto-submitted after {{ $test->violation_limit }} violations.
                </div>
                @endif

                <form method="POST" action="{{ route('student.test.start',$test) }}">
                    @csrf
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agree" required>
                        <label class="form-check-label" for="agree">I have read and agree to the test instructions and rules.</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="bi bi-play-circle me-2"></i>Start Test Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/student/result.blade.php --}}
{{-- ============================================================ --}}
{{-- Save this as a separate file at the path above --}}