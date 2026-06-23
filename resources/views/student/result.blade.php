{{-- FILE PATH: resources/views/student/result.blade.php --}}
@extends('layouts.app')
@section('title','Test Result')
@section('content')

@php
    $pct   = $attempt->percentage ?? 0;
    $color = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
    $grade = $pct >= 90 ? 'A+' : ($pct >= 80 ? 'A' : ($pct >= 70 ? 'B' : ($pct >= 60 ? 'C' : ($pct >= 50 ? 'D' : 'F'))));
@endphp

{{-- Result Header --}}
<div class="card border-0 shadow bg-{{ $color }} text-white mb-4">
    <div class="card-body p-3 p-md-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-7">
                <h5 class="fw-bold mb-1">{{ $test->title }}</h5>
                <div class="opacity-75 small">{{ auth()->user()->name }}</div>
                @if(auth()->user()->roll_number)
                <div class="opacity-75 small">Roll: {{ auth()->user()->roll_number }}</div>
                @endif
                <small class="opacity-75">Submitted: {{ $attempt->submitted_at?->format('d M Y, h:i A') }}</small>
            </div>
            <div class="col-12 col-md-5 text-center">
                <div class="display-4 fw-bold lh-1">{{ $pct }}%</div>
                <div class="fs-2 fw-bold mt-1">{{ $grade }}</div>
                @if($attempt->rank)
                <div class="badge bg-white text-{{ $color }} px-3 py-2 mt-2 fs-6">🏆 Rank #{{ $attempt->rank }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Marks','val'=>$attempt->obtained_marks.' / '.$attempt->total_marks,'icon'=>'bi-clipboard-check','color'=>'primary'],
        ['label'=>'Percentage','val'=>$attempt->percentage.'%','icon'=>'bi-percent','color'=>$color],
        ['label'=>'Time Taken','val'=>$attempt->time_taken_seconds ? gmdate('H:i:s',$attempt->time_taken_seconds) : 'N/A','icon'=>'bi-stopwatch','color'=>'info'],
        ['label'=>'Violations','val'=>$attempt->violation_count,'icon'=>'bi-exclamation-triangle','color'=>$attempt->violation_count>0?'danger':'success'],
    ] as $s)
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 text-center">
                <i class="bi {{ $s['icon'] }} fs-4 text-{{ $s['color'] }}"></i>
                <div class="fw-bold fs-5 mt-1">{{ $s['val'] }}</div>
                <div class="text-muted small">{{ $s['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($attempt->submission_reason)
<div class="alert alert-{{ $attempt->status==='auto_submitted'?'warning':'info' }} small mb-4 py-2">
    <i class="bi bi-info-circle me-2"></i><strong>Submission note:</strong> {{ $attempt->submission_reason }}
</div>
@endif

{{-- Detailed Review --}}
@if($test->result_visibility === 'detailed' && $test->result_published)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Answer Review</h6>
    </div>
    <div class="card-body p-0">
        @foreach($attempt->answers as $i => $answer)
        @php
            $q = $answer->question;
            $isCorrect = $answer->is_correct;
            $selected  = $answer->selected_option;
        @endphp
        <div class="border-bottom p-3">
            <div class="d-flex gap-2 mb-2 flex-wrap">
                <span class="badge bg-{{ $isCorrect?'success':($selected?'danger':'secondary') }}">
                    {{ $isCorrect?'✓ Correct':($selected?'✗ Wrong':'Not Answered') }}
                </span>
                <span class="badge bg-warning text-dark">{{ $answer->marks_awarded >= 0 ? '+' : '' }}{{ $answer->marks_awarded }} marks</span>
                <span class="text-muted small">Q{{ $i+1 }}</span>
            </div>
            <p class="fw-semibold small mb-2">{{ $q->statement }}</p>
            <div class="row g-1">
                @foreach($q->options as $key => $val)
                <div class="col-12 col-sm-6">
                    <div class="p-2 rounded small {{ $key===$q->correct_answer?'bg-success bg-opacity-15 border border-success':($key===$selected&&!$isCorrect?'bg-danger bg-opacity-15 border border-danger':'bg-light') }}">
                        <span class="fw-bold">{{ strtoupper($key) }})</span> {{ $val }}
                        @if($key===$q->correct_answer)<span class="text-success fw-bold ms-1">✓</span>@endif
                        @if($key===$selected&&!$isCorrect)<span class="text-danger fw-bold ms-1">✗</span>@endif
                    </div>
                </div>
                @endforeach
            </div>
            @if($q->explanation)
            <div class="alert alert-info small mt-2 mb-0 py-2">
                <strong>Explanation:</strong> {{ $q->explanation }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="d-flex gap-2 mt-4 justify-content-center flex-wrap no-print">
    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary btn-sm px-3">
        <i class="bi bi-house me-1"></i>Dashboard
    </a>
    <a href="{{ route('student.my-tests') }}" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-journal-check me-1"></i>My Tests
    </a>
    <button onclick="window.print()" class="btn btn-outline-dark btn-sm px-3">
        <i class="bi bi-printer me-1"></i>Print
    </button>
</div>

@endsection