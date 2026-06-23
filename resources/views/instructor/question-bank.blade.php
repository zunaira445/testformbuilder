{{-- FILE PATH: resources/views/instructor/question-bank.blade.php --}}
@extends('layouts.app')
@section('title', 'Question Bank')
@section('content')

@php
    $user = auth()->user();
    $testIds = \App\Models\Test::where('user_id', $user->id)->pluck('id');
    $sectionIds = \App\Models\TestSection::whereIn('test_id', $testIds)->pluck('id');
    $questions = \App\Models\Question::whereIn('test_section_id', $sectionIds)
                    ->where('in_question_bank', true)
                    ->with('section.test')
                    ->latest()
                    ->paginate(20);
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-collection me-2 text-primary"></i>Question Bank</h4>
        <small class="text-muted">Questions saved for reuse across tests</small>
    </div>
    <span class="badge bg-primary fs-6 px-3 py-2">{{ $questions->total() }} Questions</span>
</div>

@if($questions->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-collection display-3 text-muted mb-3 d-block"></i>
        <h5 class="fw-bold">Question Bank is Empty</h5>
        <p class="text-muted">When editing questions, toggle <strong>"Save to Question Bank"</strong> to add them here for reuse.</p>
        <a href="{{ route('instructor.tests.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-journal-text me-1"></i>Go to My Tests
        </a>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @foreach($questions as $i => $q)
        <div class="border-bottom px-4 py-3 {{ $loop->last ? 'border-bottom-0' : '' }}">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge bg-light text-dark border">Q{{ ($questions->currentPage()-1)*20 + $i + 1 }}</span>
                        <span class="badge bg-{{ $q->is_active ? 'success' : 'secondary' }} bg-opacity-75 small">
                            {{ $q->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="badge bg-warning text-dark small">{{ $q->marks }} mark{{ $q->marks != 1 ? 's' : '' }}</span>
                        <span class="badge bg-info text-dark small">
                            <i class="bi bi-journal me-1"></i>{{ Str::limit($q->section->test->title, 25) }}
                        </span>
                    </div>
                    <p class="mb-2 fw-semibold">{{ $q->statement }}</p>
                    <div class="row g-1 small">
                        @foreach(['a','b','c','d'] as $opt)
                        <div class="col-md-6">
                            <span class="{{ $q->correct_answer === $opt ? 'text-success fw-semibold' : 'text-muted' }}">
                                <i class="bi bi-{{ $q->correct_answer === $opt ? 'check-circle-fill' : 'circle' }} me-1"></i>
                                {{ strtoupper($opt) }}) {{ Str::limit($q->{'option_'.$opt}, 60) }}
                            </span>
                        </div>
                        @endforeach
                        @if($q->option_e)
                        <div class="col-md-6">
                            <span class="{{ $q->correct_answer === 'e' ? 'text-success fw-semibold' : 'text-muted' }}">
                                <i class="bi bi-{{ $q->correct_answer === 'e' ? 'check-circle-fill' : 'circle' }} me-1"></i>
                                E) {{ Str::limit($q->option_e, 60) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @if($q->explanation)
                    <div class="mt-2 p-2 bg-info bg-opacity-10 rounded small text-info">
                        <i class="bi bi-lightbulb me-1"></i><strong>Explanation:</strong> {{ Str::limit($q->explanation, 100) }}
                    </div>
                    @endif
                </div>
                <div class="btn-group-vertical btn-group-sm">
                    <a href="{{ route('instructor.tests.edit', $q->section->test) }}"
                       class="btn btn-outline-primary" title="Edit in Test">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card-footer bg-transparent">
        {{ $questions->links() }}
    </div>
</div>
@endif

@endsection