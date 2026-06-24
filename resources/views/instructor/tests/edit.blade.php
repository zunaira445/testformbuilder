{{-- FILE PATH: resources/views/instructor/tests/edit.blade.php --}}
@extends('layouts.app')
@section('title','Edit Test')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('instructor.tests.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="fw-bold mb-0">{{ $test->title }}</h4>
            <small class="text-muted">Code: <code>{{ $test->test_code }}</code> | Mode {{ $test->mode }} | {{ $test->duration_minutes }} min</small>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        {{-- Share Link --}}
        <button class="btn btn-sm btn-outline-info" onclick="navigator.clipboard.writeText('{{ $test->share_link }}');alert('Test link copied!')">
            <i class="bi bi-share me-1"></i>Share Link
        </button>
        {{-- Toggle Open/Close --}}
        <form method="POST" action="{{ route('instructor.tests.toggle-open',$test) }}" class="d-inline">
            @csrf
            <button class="btn btn-sm {{ $test->is_open ? 'btn-warning' : 'btn-success' }}">
                <i class="bi {{ $test->is_open ? 'bi-lock' : 'bi-unlock' }} me-1"></i>{{ $test->is_open ? 'Close Test' : 'Open Test' }}
            </button>
        </form>
        {{-- Duplicate --}}
        <form method="POST" action="{{ route('instructor.tests.duplicate',$test) }}" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-copy me-1"></i>Duplicate</button>
        </form>
        {{-- Export --}}
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Export</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('instructor.tests.export.pdf',$test) }}"><i class="bi bi-file-pdf text-danger me-2"></i>PDF</a></li>
                <li><a class="dropdown-item" href="{{ route('instructor.tests.export.excel',$test) }}"><i class="bi bi-file-excel text-success me-2"></i>Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('instructor.tests.export.csv',$test) }}"><i class="bi bi-filetype-csv text-info me-2"></i>CSV</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT: Edit Test Details --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Test Settings</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('instructor.tests.update',$test) }}">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control form-control-sm" value="{{ $test->title }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Category</label>

<input
    type="text"
    name="category"
    class="form-control form-control-sm"
    value="{{ $test->category }}"
    placeholder="Enter category">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Duration (min)</label>
                        <input type="number" name="duration_minutes" class="form-control form-control-sm" value="{{ $test->duration_minutes }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Mode</label>
                        <select name="mode" class="form-select form-select-sm">
                            <option value="A" {{ $test->mode=='A'?'selected':'' }}>Mode A (One at a time)</option>
                            <option value="B" {{ $test->mode=='B'?'selected':'' }}>Mode B (All on page)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Start At</label>
                        <input type="datetime-local" name="start_at" class="form-control form-control-sm" value="{{ $test->start_at?->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">End At</label>
                        <input type="datetime-local" name="end_at" class="form-control form-control-sm" value="{{ $test->end_at?->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Result Visibility</label>
                        <select name="result_visibility" class="form-select form-select-sm">
                            <option value="hidden" {{ $test->result_visibility=='hidden'?'selected':'' }}>Hidden</option>
                            <option value="marks_only" {{ $test->result_visibility=='marks_only'?'selected':'' }}>Marks Only</option>
                            <option value="detailed" {{ $test->result_visibility=='detailed'?'selected':'' }}>Detailed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Violation Limit</label>
                        <select name="violation_limit" class="form-select form-select-sm">
                            @foreach([1,2,3,4,5] as $v)
                            <option value="{{ $v }}" {{ $test->violation_limit==$v?'selected':'' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Toggle switches --}}
                    @foreach([
                        ['name'=>'random_questions','label'=>'Random Questions','val'=>$test->random_questions],
                        ['name'=>'random_options','label'=>'Random Options','val'=>$test->random_options],
                        ['name'=>'anti_cheat','label'=>'Anti-Cheat','val'=>$test->anti_cheat],
                        ['name'=>'negative_marking','label'=>'Negative Marking','val'=>$test->negative_marking],
                    ] as $toggle)
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="{{ $toggle['name'] }}" value="0">
                        <input class="form-check-input" type="checkbox" name="{{ $toggle['name'] }}" value="1" {{ $toggle['val']?'checked':'' }}>
                        <label class="form-check-label small">{{ $toggle['label'] }}</label>
                    </div>
                    @endforeach
                    @if($test->negative_marking)
                    <div class="mb-3">
                        <label class="form-label small">Negative Marks</label>
                        <input type="number" name="negative_marks" class="form-control form-control-sm" value="{{ $test->negative_marks }}" step="0.25" min="0">
                    </div>
                    @else
                    <input type="hidden" name="negative_marks" value="0">
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-save me-1"></i>Save Changes
                    </button>
                </form>

                @if($test->attempts()->whereIn('status',['submitted','auto_submitted'])->exists())
                <hr>
                <form method="POST" action="{{ route('instructor.tests.publish',$test) }}">
                    @csrf
                    <button class="btn btn-success btn-sm w-100 {{ $test->result_published?'disabled':'' }}">
                        <i class="bi bi-trophy me-1"></i>{{ $test->result_published?'Results Published':'Publish Results & Ranks' }}
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Add Section --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-square me-2"></i>Add Section</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('instructor.sections.store',$test) }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="Section title e.g. Section 2" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Description (optional)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">Add Section</button>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Sections & Questions --}}
    <div class="col-lg-8">
        @php $totalQ = 0; $totalM = 0; @endphp
        @forelse($test->sections as $section)
        @php $totalQ += $section->questions->count(); $totalM += $section->questions->sum('marks'); @endphp

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                <div>
                    <span class="fw-bold">{{ $section->title }}</span>
                    <span class="badge bg-primary ms-2">{{ $section->questions->count() }} Q</span>
                    <span class="badge bg-secondary ms-1">{{ $section->questions->sum('marks') }} Marks</span>
                </div>
                <form method="POST" action="{{ route('instructor.sections.destroy',$section) }}" onsubmit="return confirm('Delete this section and all its questions?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </div>

            {{-- Questions List --}}
            <div class="card-body p-0">
                @forelse($section->questions as $i => $q)
                <div class="border-bottom px-4 py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1 me-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-light text-dark border">Q{{ $i+1 }}</span>
                                <span class="badge bg-{{ $q->is_active?'success':'secondary' }} bg-opacity-75 small">{{ $q->is_active?'Active':'Inactive' }}</span>
                                <span class="badge bg-warning text-dark small">{{ $q->marks }} mark{{ $q->marks!=1?'s':'' }}</span>
                            </div>
                            <p class="mb-1 small fw-semibold">{{ Str::limit($q->statement,120) }}</p>
                            <div class="row g-1 small text-muted">
                                @foreach(['a','b','c','d'] as $opt)
                                <div class="col-6">
                                    <span class="{{ $q->correct_answer==$opt?'text-success fw-semibold':'' }}">
                                        {{ strtoupper($opt) }}) {{ Str::limit($q->{'option_'.$opt},50) }}
                                        {{ $q->correct_answer==$opt?'✓':'' }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="btn-group-vertical btn-group-sm">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editQ{{ $q->id }}"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('instructor.questions.toggle',$q) }}">@csrf<button class="btn btn-outline-{{ $q->is_active?'warning':'success' }}"><i class="bi bi-toggle-{{ $q->is_active?'on':'off' }}"></i></button></form>
                            <form method="POST" action="{{ route('instructor.questions.destroy',$q) }}" onsubmit="return confirm('Delete this question?')">@csrf @method('DELETE')<button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editQ{{ $q->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Question</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('instructor.questions.update',$q) }}">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    @include('instructor.tests.partials.question-form',['q'=>$q])
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Question</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3 small">No questions in this section yet.</div>
                @endforelse
            </div>

            {{-- Add Question Form --}}
            <div class="card-footer bg-light p-3">
                <button class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="collapse" data-bs-target="#addQ{{ $section->id }}">
                    <i class="bi bi-plus-circle me-1"></i>Add Question to {{ $section->title }}
                </button>
                <div class="collapse mt-3" id="addQ{{ $section->id }}">
                    <form method="POST" action="{{ route('instructor.questions.store',$section) }}">
                        @csrf
                        @include('instructor.tests.partials.question-form',['q'=>null])
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary btn-sm px-4"><i class="bi bi-plus me-1"></i>Add Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-layers display-4"></i>
            <p class="mt-3">No sections yet. Add a section from the left panel.</p>
        </div>
        @endforelse

        @if($test->sections->isNotEmpty())
        <div class="alert alert-info d-flex align-items-center gap-2">
            <i class="bi bi-info-circle"></i>
            <div>Total: <strong>{{ $totalQ }} Questions</strong> | Total Marks: <strong>{{ $totalM }}</strong></div>
        </div>
        @endif
    </div>
</div>
@endsection