{{--
====================================================================
FILE PATH: resources/views/instructor/tests/create.blade.php
====================================================================
--}}
@extends('layouts.app')
@section('title', 'Create New Test')

@push('styles')
<style>
/* ─── WIZARD STEPS ─────────────────────────────────────── */
.wizard-steps { display:flex; align-items:center; gap:0; margin-bottom:2rem; overflow-x:auto; padding-bottom:4px; }
.w-step { display:flex; align-items:center; flex-shrink:0; flex-direction:column; align-items:center; }
.w-step-dot {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:.85rem;
    background:var(--bs-secondary-bg); border:2px solid var(--bs-border-color);
    color:var(--bs-secondary-color); transition:all .3s;
}
.w-step.active .w-step-dot { background:#1e40af; border-color:#1e40af; color:#fff; box-shadow:0 0 0 4px rgba(30,64,175,.2); }
.w-step.done   .w-step-dot { background:#059669; border-color:#059669; color:#fff; }
.w-step-label  { font-size:.72rem; margin-top:.3rem; font-weight:600; color:var(--bs-secondary-color); white-space:nowrap; }
.w-step.active .w-step-label,
.w-step.done   .w-step-label { color:var(--bs-body-color); }
.w-connector { flex:1; height:2px; background:var(--bs-border-color); min-width:24px; margin-bottom:22px; }

/* ─── SECTION CARDS ────────────────────────────────────── */
.section-builder-card { border:2px solid var(--bs-border-color); border-radius:14px; overflow:hidden; transition:border-color .2s; }
.section-builder-card:focus-within { border-color:#3b82f6; }
.section-header-bar {
    background:linear-gradient(90deg,rgba(30,64,175,.08),transparent);
    padding:.75rem 1.1rem;
    display:flex; align-items:center; justify-content:space-between;
    border-bottom:1px solid var(--bs-border-color);
    cursor:pointer; user-select:none;
}
.section-badge-num { background:#1e40af; color:#fff; border-radius:6px; padding:.15rem .55rem; font-size:.75rem; font-weight:700; margin-right:.5rem; }

/* ─── MCQ CARD ─────────────────────────────────────────── */
.mcq-card { border:1px solid var(--bs-border-color); border-radius:10px; padding:1rem 1.1rem; position:relative; background:var(--bs-body-bg); transition:box-shadow .2s; }
.mcq-card:hover { box-shadow:0 3px 14px rgba(0,0,0,.07); }
.mcq-remove-btn { position:absolute; top:.5rem; right:.5rem; }

/* ─── TOGGLE LABELS ────────────────────────────────────── */
.toggle-label { display:flex; align-items:center; justify-content:space-between; padding:.7rem 1rem; border:1.5px solid var(--bs-border-color); border-radius:10px; cursor:pointer; transition:all .2s; }
.toggle-label:hover { border-color:#3b82f6; }

/* ─── BULK PREVIEW ─────────────────────────────────────── */
.bulk-preview-item { background:var(--bs-secondary-bg); border:1px solid var(--bs-border-color); border-radius:8px; padding:.6rem .9rem; margin-bottom:.5rem; font-size:.85rem; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('instructor.tests.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Create New Test</h4>
            <small class="text-muted">Build your exam step by step</small>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button form="mainTestForm" type="submit" name="action" value="draft"
                class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-floppy me-1"></i>Save Draft
        </button>
        <button form="mainTestForm" type="submit" name="action" value="publish"
                class="btn btn-primary btn-sm fw-semibold">
            <i class="bi bi-send me-1"></i>Create Test
        </button>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible mb-3" style="border-radius:12px">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Wizard Steps --}}
<div class="wizard-steps mb-4">
    @foreach([['1','Test Info'],['2','Sections & Questions'],['3','Settings'],['4','Review']] as $i => $s)
    <div class="w-step {{ $i===0?'active':'' }}" id="wstep-{{ $i }}">
        <div class="w-step-dot">{{ $s[0] }}</div>
        <div class="w-step-label">{{ $s[1] }}</div>
    </div>
    @if($i < 3)<div class="w-connector" id="wconn-{{ $i }}"></div>@endif
    @endforeach
</div>

<form id="mainTestForm" method="POST" action="{{ route('instructor.tests.store') }}">
@csrf

<div class="row g-4">

{{-- ═══════════════════ LEFT COLUMN ═══════════════════ --}}
<div class="col-xl-8">

    {{-- STEP 1: Test Information --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3 border-bottom">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-pill">1</span>Test Information
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="mb-3">
                <label class="form-label fw-semibold">Test Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg"
                       value="{{ old('title') }}"
                       placeholder="e.g. Biology Chapter 5 — Midterm Exam"
                       required oninput="updatePreviewTitle(this.value)">
                <div class="form-text">Students will see this as the exam title.</div>
            </div>

            <div class="row g-3">
                {{-- ── CATEGORY: Free text input (no dropdown) ── --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category / Subject</label>
                    <input type="text" name="category" class="form-control"
                           value="{{ old('category') }}"
                           placeholder="e.g. Biology, Mathematics, English">
                    <div class="form-text">Type any subject or category name.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Duration <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="duration_minutes" class="form-control"
                               value="{{ old('duration_minutes', 60) }}"
                               min="1" max="480" required
                               oninput="document.getElementById('previewDuration').textContent=this.value+' min'">
                        <span class="input-group-text">minutes</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Start Date & Time</label>
                    <input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at') }}">
                    <div class="form-text">Leave blank to open manually.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">End Date & Time</label>
                    <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at') }}">
                    <div class="form-text">Leave blank for no deadline.</div>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2"
                          placeholder="Brief description shown to students before starting...">{{ old('description') }}</textarea>
            </div>
            <div class="mt-3">
                <label class="form-label fw-semibold">Instructions for Students</label>
                <textarea name="instructions" class="form-control" rows="4"
                          placeholder="• Read all questions carefully.&#10;• Do not switch tabs.&#10;• Submit before time expires.">{{ old('instructions') }}</textarea>
            </div>
        </div>
    </div>

    {{-- STEP 2: Sections & Questions --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-pill">2</span>Sections & Questions
            </h6>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSection()">
                <i class="bi bi-plus-circle me-1"></i>Add Section
            </button>
        </div>
        <div class="card-body p-4">
            <div id="sectionsContainer">
                {{-- Default Section 1 injected by JS on DOMContentLoaded --}}
            </div>
            <p class="text-center text-muted small mt-2">
                <i class="bi bi-info-circle me-1"></i>
                Click a section header to collapse/expand it.
            </p>
        </div>
    </div>

</div>{{-- /col-xl-8 --}}

{{-- ═══════════════════ RIGHT COLUMN ═══════════════════ --}}
<div class="col-xl-4">
<div style="position:sticky;top:70px">

    {{-- STEP 3: Settings --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-transparent py-3 border-bottom">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-pill">3</span>Exam Settings
            </h6>
        </div>
        <div class="card-body p-3">

            {{-- Mode --}}
            <div class="mb-3">
                <label class="form-label fw-semibold small">Display Mode <span class="text-danger">*</span></label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="mode" id="modeA" value="A" checked>
                        <label class="btn btn-outline-primary w-100 text-start p-2" for="modeA" style="font-size:.8rem">
                            <strong class="d-block">Mode A</strong>
                            <span class="text-muted">One question at a time</span>
                        </label>
                    </div>
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="mode" id="modeB" value="B">
                        <label class="btn btn-outline-primary w-100 text-start p-2" for="modeB" style="font-size:.8rem">
                            <strong class="d-block">Mode B</strong>
                            <span class="text-muted">All questions visible</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Result Visibility --}}
            <div class="mb-3">
                <label class="form-label fw-semibold small">Result Visibility</label>
                <select name="result_visibility" class="form-select form-select-sm">
                    <option value="hidden">Hidden (Publish manually)</option>
                    <option value="marks_only">Show Marks Only</option>
                    <option value="detailed">Show Detailed Result</option>
                </select>
            </div>

            {{-- Max Attempts --}}
            <div class="mb-3">
                <label class="form-label fw-semibold small">Max Attempts per Student</label>
                <input type="number" name="max_attempts" class="form-control form-control-sm" value="1" min="1">
            </div>

            {{-- Violation Limit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold small">Auto-Submit After (violations)</label>
                <div class="input-group input-group-sm">
                    <select name="violation_limit" class="form-select">
                        @foreach([1,2,3,4,5] as $v)
                        <option value="{{ $v }}" {{ $v===3?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-text">violations</span>
                </div>
            </div>

            <hr class="my-3">

            {{-- Toggle Options --}}
            @foreach([
                ['random_questions','Randomize Question Order','bi-shuffle'],
                ['random_options','Randomize Options Order','bi-arrow-repeat'],
                ['anti_cheat','Enable Anti-Cheat System','bi-shield-lock'],
                ['negative_marking','Enable Negative Marking','bi-dash-circle'],
            ] as [$fname,$flabel,$ficon])
            <div class="mb-2">
                <input type="hidden" name="{{ $fname }}" value="0">
                <label class="toggle-label" for="{{ $fname }}">
                    <span class="d-flex align-items-center gap-2 small fw-semibold">
                        <i class="bi {{ $ficon }} text-primary"></i>{{ $flabel }}
                    </span>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="{{ $fname }}"
                               id="{{ $fname }}" value="1"
                               {{ $fname==='anti_cheat' ? 'checked' : '' }}
                               {{ $fname==='negative_marking' ? 'onchange="toggleNegMarks(this)"' : '' }}>
                    </div>
                </label>
            </div>
            @endforeach

            {{-- Negative Marks Amount (hidden by default) --}}
            <div id="negMarksField" class="mt-2 d-none p-3" style="background:#fef2f2;border-radius:10px;border:1px solid #fecaca">
                <label class="form-label small fw-semibold text-danger">
                    <i class="bi bi-dash-circle me-1"></i>Marks Deducted Per Wrong Answer
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">-</span>
                    <input type="number" name="negative_marks" id="negMarksInput"
                           class="form-control" value="0.25" step="0.25" min="0.25" max="10">
                    <span class="input-group-text">marks</span>
                </div>
                <div class="form-text mt-1" style="font-size:11px">
                    Common values: 0.25, 0.5, 1
                </div>
            </div>

        </div>
    </div>

    {{-- Preview Card --}}
    <div class="card border-0 shadow-sm border-primary" style="border-width:2px!important">
        <div class="card-header bg-primary text-white py-2">
            <small class="fw-bold"><i class="bi bi-eye me-1"></i>Quick Preview</small>
        </div>
        <div class="card-body p-3 small">
            <div class="fw-bold mb-1" id="previewTitle" style="font-size:.95rem">Untitled Test</div>
            <div class="text-muted" id="previewStats">0 sections • 0 questions</div>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <span class="badge bg-light text-dark border" id="previewMode">Mode A</span>
                <span class="badge bg-light text-dark border" id="previewDuration">60 min</span>
                <span class="badge bg-light text-dark border" id="previewMarks">0 marks</span>
            </div>
        </div>
    </div>

</div>
</div>{{-- /col-xl-4 --}}

</div>{{-- /row --}}
</form>
@endsection

@push('scripts')
<script>
"use strict";

/* ═══════════════════════════════════════════
   STATE
═══════════════════════════════════════════ */
let sectionCount = 0;
let mcqCounters  = {};   // sectionIdx -> next mcq index
let collapsed    = {};

/* ═══════════════════════════════════════════
   SECTION MANAGEMENT
═══════════════════════════════════════════ */
function buildSectionHTML(idx) {
    return `
    <div class="section-builder-card mb-4" id="section-${idx}" data-section="${idx}">
        <div class="section-header-bar" onclick="toggleSection(${idx})">
            <div class="d-flex align-items-center gap-2">
                <span class="section-badge-num">S${idx+1}</span>
                <input type="text" name="sections[${idx}][title]"
                       class="form-control form-control-sm fw-semibold"
                       value="Section ${idx+1}"
                       style="max-width:200px;border:none;background:transparent;font-size:.9rem;padding:0"
                       placeholder="Section name"
                       onclick="event.stopPropagation()">
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary" id="qcount-${idx}">0 Q</span>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="event.stopPropagation();removeSection(${idx})">
                    <i class="bi bi-trash3"></i>
                </button>
                <i class="bi bi-chevron-down" id="chevron-${idx}"></i>
            </div>
        </div>
        <div class="p-3" id="sectionBody-${idx}">
            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-primary btn-sm active" id="tabManual-${idx}"
                        onclick="switchMethod(${idx},'manual')">
                    <i class="bi bi-pencil me-1"></i>Manual Entry
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="tabBulk-${idx}"
                        onclick="switchMethod(${idx},'bulk')">
                    <i class="bi bi-textarea-t me-1"></i>Bulk Paste
                </button>
            </div>
            <div id="methodManual-${idx}">
                <div id="mcqList-${idx}" class="mb-2"></div>
                <button type="button" class="btn btn-sm btn-outline-primary w-100"
                        style="border-style:dashed" onclick="addMCQ(${idx})">
                    <i class="bi bi-plus-circle me-1"></i>Add Question
                </button>
            </div>
            <div id="methodBulk-${idx}" class="d-none">
                <div class="alert alert-info small p-2 mb-2">
                    <strong>Format:</strong><br>
                    <code>Q. Question text<br>A. Option A<br>B. Option B<br>C. Option C<br>D. Option D<br>ANS: B</code>
                </div>
                <textarea class="form-control font-monospace mb-2" id="bulkPaste-${idx}" rows="10"
                          placeholder="Paste your questions here..."></textarea>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="parseBulk(${idx})">
                        <i class="bi bi-lightning me-1"></i>Parse & Import
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearBulk(${idx})">
                        <i class="bi bi-trash me-1"></i>Clear
                    </button>
                </div>
                <div id="bulkPreview-${idx}" class="mt-3"></div>
            </div>
        </div>
    </div>`;
}

function addSection() {
    const idx       = sectionCount++;
    mcqCounters[idx] = 0;
    document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', buildSectionHTML(idx));
    refreshPreview();
}

function removeSection(idx) {
    if (!confirm('Remove this section and all its questions?')) return;
    document.getElementById('section-' + idx)?.remove();
    refreshPreview();
}

function toggleSection(idx) {
    const body    = document.getElementById('sectionBody-' + idx);
    const chevron = document.getElementById('chevron-' + idx);
    if (!body) return;
    collapsed[idx] = !collapsed[idx];
    body.style.display    = collapsed[idx] ? 'none' : '';
    chevron.className     = collapsed[idx] ? 'bi bi-chevron-right' : 'bi bi-chevron-down';
}

function switchMethod(idx, method) {
    document.getElementById('methodManual-' + idx).classList.toggle('d-none', method !== 'manual');
    document.getElementById('methodBulk-'   + idx).classList.toggle('d-none', method !== 'bulk');
    document.getElementById('tabManual-'    + idx).className = `btn btn-sm ${method==='manual'?'btn-primary active':'btn-outline-secondary'}`;
    document.getElementById('tabBulk-'      + idx).className = `btn btn-sm ${method==='bulk'?'btn-primary active':'btn-outline-secondary'}`;
}

/* ═══════════════════════════════════════════
   MCQ MANAGEMENT
═══════════════════════════════════════════ */
function addMCQ(sIdx, prefill = {}) {
    if (mcqCounters[sIdx] === undefined) mcqCounters[sIdx] = 0;
    const mIdx = mcqCounters[sIdx]++;

    const html = `
    <div class="mcq-card mb-3" id="mcq-${sIdx}-${mIdx}">
        <button type="button" class="btn btn-sm btn-outline-danger mcq-remove-btn"
                onclick="removeMCQ(${sIdx},${mIdx})">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="mb-2">
            <label class="form-label small fw-semibold">Question Statement <span class="text-danger">*</span></label>
            <textarea name="sections[${sIdx}][questions][${mIdx}][statement]"
                      class="form-control form-control-sm" rows="2" required
                      placeholder="Enter the question here...">${prefill.statement || ''}</textarea>
        </div>
        <div class="row g-2 mb-2">
            ${['a','b','c','d'].map(k => `
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text fw-bold" style="width:32px">${k.toUpperCase()}</span>
                    <input type="text" name="sections[${sIdx}][questions][${mIdx}][option_${k}]"
                           class="form-control" placeholder="Option ${k.toUpperCase()}"
                           value="${prefill['option_'+k] || ''}" required>
                </div>
            </div>`).join('')}
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text fw-bold" style="width:32px">E</span>
                    <input type="text" name="sections[${sIdx}][questions][${mIdx}][option_e]"
                           class="form-control" placeholder="Option E (optional)"
                           value="${prefill.option_e || ''}">
                </div>
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Correct Answer</label>
                <select name="sections[${sIdx}][questions][${mIdx}][correct_answer]"
                        class="form-select form-select-sm" required>
                    ${['a','b','c','d','e'].map(k =>
                        `<option value="${k}" ${prefill.correct===k?'selected':''}>${k.toUpperCase()}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Marks</label>
                <input type="number" name="sections[${sIdx}][questions][${mIdx}][marks]"
                       class="form-control form-control-sm"
                       value="${prefill.marks || 1}" step="0.25" min="0.25" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-semibold mb-1">Explanation (optional)</label>
                <input type="text" name="sections[${sIdx}][questions][${mIdx}][explanation]"
                       class="form-control form-control-sm" placeholder="Why is this correct?"
                       value="${prefill.explanation || ''}">
            </div>
        </div>
    </div>`;

    document.getElementById('mcqList-' + sIdx).insertAdjacentHTML('beforeend', html);
    updateQCount(sIdx);
    refreshPreview();
}

function removeMCQ(sIdx, mIdx) {
    document.getElementById(`mcq-${sIdx}-${mIdx}`)?.remove();
    updateQCount(sIdx);
    refreshPreview();
}

function updateQCount(sIdx) {
    const count = document.querySelectorAll(`#mcqList-${sIdx} .mcq-card`).length;
    const el    = document.getElementById('qcount-' + sIdx);
    if (el) el.textContent = count + ' Q';
}

/* ═══════════════════════════════════════════
   BULK PASTE PARSER  ─ FIXED
═══════════════════════════════════════════ */
function parseBulk(sIdx) {
    const raw = (document.getElementById('bulkPaste-' + sIdx)?.value || '').trim();
    if (!raw) { alert('Please paste some questions first.'); return; }

    const blocks = raw.split(/\n\s*\n+/).filter(b => b.trim());
    const parsed = [];

    blocks.forEach(block => {
        const lines = block.split('\n').map(l => l.trim()).filter(Boolean);
        const q = {
            statement: '', option_a: '', option_b: '', option_c: '',
            option_d: '', option_e: '', correct: 'a', marks: 1, explanation: ''
        };

        lines.forEach(line => {
            if      (/^q[\.\)\:]\s*/i.test(line)) q.statement = line.replace(/^q[\.\)\:]\s*/i,'').trim();
            else if (/^a[\.\)\:]\s*/i.test(line)) q.option_a  = line.replace(/^a[\.\)\:]\s*/i,'').trim();
            else if (/^b[\.\)\:]\s*/i.test(line)) q.option_b  = line.replace(/^b[\.\)\:]\s*/i,'').trim();
            else if (/^c[\.\)\:]\s*/i.test(line)) q.option_c  = line.replace(/^c[\.\)\:]\s*/i,'').trim();
            else if (/^d[\.\)\:]\s*/i.test(line)) q.option_d  = line.replace(/^d[\.\)\:]\s*/i,'').trim();
            else if (/^e[\.\)\:]\s*/i.test(line)) q.option_e  = line.replace(/^e[\.\)\:]\s*/i,'').trim();
            else if (/^ans(wer)?[\.\:\)]\s*/i.test(line)) {
                q.correct = line.replace(/^ans(wer)?[\.\:\)]\s*/i,'').trim().toLowerCase().charAt(0);
            }
            else if (/^exp(lanation)?[\.\:\)]\s*/i.test(line)) {
                q.explanation = line.replace(/^exp(lanation)?[\.\:\)]\s*/i,'').trim();
            }
        });

        if (q.statement && q.option_a && q.option_b) parsed.push(q);
    });

    const previewEl = document.getElementById('bulkPreview-' + sIdx);

    if (!parsed.length) {
        previewEl.innerHTML = `
        <div class="alert alert-warning small">
            <strong>⚠️ Could not parse any questions.</strong> Please check the format:<br>
            <code>Q. Question<br>A. Option<br>B. Option<br>C. Option<br>D. Option<br>ANS: B</code>
        </div>`;
        return;
    }

    let html = `<div class="alert alert-success small mb-2">
        <strong>✅ ${parsed.length} question(s) parsed successfully.</strong>
        Click "Import to Section" below to add them.
    </div>`;

    parsed.forEach((q, i) => {
        html += `<div class="bulk-preview-item">
            <strong>Q${i+1}.</strong> ${q.statement}
            <span class="badge bg-success ms-2">ANS: ${q.correct.toUpperCase()}</span>
        </div>`;
    });

    // Store parsed JSON safely in data attribute
    const importBtn = document.createElement('button');
    importBtn.type      = 'button';
    importBtn.className = 'btn btn-success btn-sm mt-2 w-100';
    importBtn.innerHTML = `<i class="bi bi-download me-1"></i>Import ${parsed.length} Question(s) to Section`;
    importBtn.addEventListener('click', () => importBulkToSection(sIdx, parsed));

    previewEl.innerHTML = html;
    previewEl.appendChild(importBtn);
}

function importBulkToSection(sIdx, parsed) {
    switchMethod(sIdx, 'manual');
    parsed.forEach(q => addMCQ(sIdx, q));
    clearBulk(sIdx);
    document.getElementById('bulkPreview-' + sIdx).innerHTML =
        `<div class="alert alert-success small">
            <i class="bi bi-check-circle me-1"></i>
            Questions imported successfully to the section!
         </div>`;
}

function clearBulk(sIdx) {
    const ta = document.getElementById('bulkPaste-' + sIdx);
    const pv = document.getElementById('bulkPreview-' + sIdx);
    if (ta) ta.value = '';
    if (pv) pv.innerHTML = '';
}

/* ═══════════════════════════════════════════
   LIVE PREVIEW
═══════════════════════════════════════════ */
function refreshPreview() {
    const sections  = document.querySelectorAll('[data-section]').length;
    const questions = document.querySelectorAll('.mcq-card').length;
    document.getElementById('previewStats').textContent =
        `${sections} section(s) • ${questions} question(s)`;
    document.getElementById('previewMarks').textContent = `${questions} mark(s)`;
}

function updatePreviewTitle(val) {
    document.getElementById('previewTitle').textContent = val || 'Untitled Test';
}

document.querySelectorAll('input[name="mode"]').forEach(r =>
    r.addEventListener('change', function() {
        document.getElementById('previewMode').textContent = 'Mode ' + this.value;
    })
);

/* ═══════════════════════════════════════════
   SETTINGS
═══════════════════════════════════════════ */
function toggleNegMarks(checkbox) {
    document.getElementById('negMarksField').classList.toggle('d-none', !checkbox.checked);
}

/* ═══════════════════════════════════════════
   INIT
═══════════════════════════════════════════ */
window.addEventListener('DOMContentLoaded', () => {
    addSection();           // Add Section 1 by default
    addMCQ(0);             // Add first question
});
</script>
@endpush