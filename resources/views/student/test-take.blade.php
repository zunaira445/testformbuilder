{{-- FILE PATH: resources/views/student/test-take.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ auth()->user()->dark_mode ? 'dark' : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->title }} — SWF PORTAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; user-select: none; }
        .test-topbar { background: #1e40af; color: white; padding: 10px 20px; position: sticky; top: 0; z-index: 100; }
        .question-card { border-radius: 12px; }
        .option-label { display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.2s; margin-bottom: 10px; }
        .option-label:hover { border-color: #1e40af; background: #eff6ff; }
        .option-input:checked + .option-label { border-color: #1e40af; background: #dbeafe; font-weight: 600; }
        [data-bs-theme="dark"] .option-label { border-color: #374151; }
        [data-bs-theme="dark"] .option-label:hover { border-color: #3b82f6; background: #1e3a8a; }
        [data-bs-theme="dark"] .option-input:checked + .option-label { border-color: #3b82f6; background: #1e3a8a; }
        .option-circle { width: 28px; height: 28px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0; }
        .option-input:checked + .option-label .option-circle { background: #1e40af; color: white; }
        .palette-btn { width: 36px; height: 36px; font-size: 12px; border-radius: 6px; border: none; }
        .q-answered   { background: #059669; color: white; }
        .q-unanswered { background: #e5e7eb; color: #374151; }
        .q-review     { background: #f59e0b; color: white; }
        .q-current    { background: #1e40af; color: white; outline: 3px solid #93c5fd; }
        #timer-box { background: rgba(0,0,0,0.2); border-radius: 8px; padding: 6px 14px; }
        #timer-display { font-size: 1.3rem; font-weight: 800; letter-spacing: 2px; }
        .danger-timer { animation: blink 0.8s infinite; color: #fca5a5 !important; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
        .violation-overlay { position: fixed; inset: 0; background: rgba(220,38,38,0.95); z-index: 9999; display: none; align-items: center; justify-content: center; text-align: center; color: white; }
        @media(max-width:768px) { .palette-sidebar { display: none; } }
    </style>
</head>
<body>

{{-- VIOLATION WARNING OVERLAY --}}
<div class="violation-overlay" id="violationOverlay">
    <div>
        <i class="bi bi-exclamation-triangle-fill display-1 mb-3"></i>
        <h2 class="fw-bold" id="violationTitle">WARNING!</h2>
        <p class="lead" id="violationMsg">You switched tabs. This is a violation.</p>
        <button class="btn btn-light btn-lg mt-3" id="violationDismiss" onclick="dismissViolation()">
            <i class="bi bi-arrow-return-left me-2"></i>Return to Test
        </button>
    </div>
</div>

{{-- TOP BAR --}}
<div class="test-topbar d-flex align-items-center justify-content-between">
    <div>
        <div class="fw-bold">{{ Str::limit($test->title, 40) }}</div>
        <small class="opacity-75">{{ auth()->user()->name }} | {{ auth()->user()->roll_number ?? 'N/A' }}</small>
    </div>
    <div id="timer-box" class="text-center">
        <div class="small opacity-75 mb-0">Time Remaining</div>
        <div id="timer-display">00:00:00</div>
    </div>
    <div>
        <button class="btn btn-warning fw-bold px-3" onclick="confirmSubmit()">
            <i class="bi bi-check2-all me-1"></i>Submit Test
        </button>
    </div>
</div>

<div class="container-fluid py-3">
    <div class="row g-3">
        {{-- MAIN QUESTION AREA --}}
        <div class="col-lg-8">

            @if($test->mode === 'A')
            {{-- MODE A: One question at a time --}}
            @foreach($orderedQuestions as $i => $q)
            <div class="question-slide {{ $i === 0 ? '' : 'd-none' }}" id="slide-{{ $i }}" data-index="{{ $i }}">
                <div class="card question-card shadow-sm mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold text-primary">Question {{ $i + 1 }} of {{ $orderedQuestions->count() }}</span>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-warning text-dark">{{ $q->marks }} Mark{{ $q->marks != 1 ? 's' : '' }}</span>
                            <button class="btn btn-sm btn-outline-warning" onclick="toggleReview({{ $q->id }}, this)" id="review-btn-{{ $q->id }}">
                                <i class="bi bi-flag me-1"></i>Mark Review
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="fs-5 mb-4">{{ $q->statement }}</p>
                        @foreach($q->options as $key => $value)
                        <input type="radio" name="answer_{{ $q->id }}" id="opt_{{ $q->id }}_{{ $key }}" value="{{ $key }}" class="d-none option-input"
                            {{ $answers[$q->id]?->selected_option === $key ? 'checked' : '' }}
                            onchange="saveAnswer({{ $q->id }}, '{{ $key }}', {{ $i }})">
                        <label for="opt_{{ $q->id }}_{{ $key }}" class="option-label w-100">
                            <div class="option-circle">{{ strtoupper($key) }}</div>
                            <span>{{ $value }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="card-footer d-flex justify-content-between gap-2">
                        <button class="btn btn-outline-secondary" onclick="goTo({{ $i - 1 }})" {{ $i === 0 ? 'disabled' : '' }}>
                            <i class="bi bi-arrow-left me-1"></i>Previous
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearAnswer({{ $q->id }}, {{ $i }})">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </button>
                        @if($i < $orderedQuestions->count() - 1)
                        <button class="btn btn-primary" onclick="goTo({{ $i + 1 }})">
                            Next <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        @else
                        <button class="btn btn-success fw-bold" onclick="confirmSubmit()">
                            <i class="bi bi-check2-all me-1"></i>Submit
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            @else
            {{-- MODE B: All questions on one page --}}
            @foreach($orderedQuestions as $i => $q)
            <div class="card question-card shadow-sm mb-3" id="slide-{{ $i }}">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <span class="fw-semibold">Question {{ $i + 1 }}</span>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge bg-warning text-dark">{{ $q->marks }} Mark{{ $q->marks != 1 ? 's' : '' }}</span>
                        <button class="btn btn-sm btn-outline-warning py-0" onclick="toggleReview({{ $q->id }}, this)" id="review-btn-{{ $q->id }}">
                            <i class="bi bi-flag"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <p class="mb-3">{{ $q->statement }}</p>
                    @foreach($q->options as $key => $value)
                    <input type="radio" name="answer_{{ $q->id }}" id="opt_{{ $q->id }}_{{ $key }}" value="{{ $key }}" class="d-none option-input"
                        {{ $answers[$q->id]?->selected_option === $key ? 'checked' : '' }}
                        onchange="saveAnswer({{ $q->id }}, '{{ $key }}', {{ $i }})">
                    <label for="opt_{{ $q->id }}_{{ $key }}" class="option-label w-100">
                        <div class="option-circle">{{ strtoupper($key) }}</div>
                        <span>{{ $value }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
            <div class="text-center mt-3 mb-5">
                <button class="btn btn-success btn-lg fw-bold px-5" onclick="confirmSubmit()">
                    <i class="bi bi-check2-all me-2"></i>Submit Test
                </button>
            </div>
            @endif
        </div>

        {{-- PALETTE SIDEBAR --}}
        <div class="col-lg-4 palette-sidebar">
            <div class="card shadow-sm" style="position:sticky;top:70px">
                <div class="card-header py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3 me-2"></i>Question Palette</h6>
                </div>
                <div class="card-body">
                    {{-- Legend --}}
                    <div class="d-flex gap-2 mb-3 flex-wrap small">
                        <span><span class="badge" style="background:#059669">A</span> Answered</span>
                        <span><span class="badge bg-secondary">U</span> Unanswered</span>
                        <span><span class="badge" style="background:#f59e0b">R</span> Review</span>
                        <span><span class="badge" style="background:#1e40af">C</span> Current</span>
                    </div>
                    <div class="d-flex flex-wrap gap-1" id="palette">
                        @foreach($orderedQuestions as $i => $q)
                        @php
                            $ans = $answers[$q->id] ?? null;
                            $cls = 'q-unanswered';
                            if ($ans?->is_marked_review) $cls = 'q-review';
                            elseif ($ans?->selected_option) $cls = 'q-answered';
                            if ($i === 0 && $test->mode === 'A') $cls = 'q-current';
                        @endphp
                        <button class="palette-btn {{ $cls }}" id="pal-{{ $i }}" onclick="{{ $test->mode==='A' ? "goTo($i)" : "document.getElementById('slide-$i').scrollIntoView({behavior:'smooth'})" }}" title="Q{{ $i+1 }}">
                            {{ $i + 1 }}
                        </button>
                        @endforeach
                    </div>

                    <hr>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Questions:</span>
                            <strong>{{ $orderedQuestions->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Answered:</span>
                            <strong id="stat-answered" class="text-success">{{ $answers->whereNotNull('selected_option')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Unanswered:</span>
                            <strong id="stat-unanswered" class="text-danger">{{ $answers->whereNull('selected_option')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Marked Review:</span>
                            <strong id="stat-review" class="text-warning">{{ $answers->where('is_marked_review',true)->count() }}</strong>
                        </div>
                    </div>

                    <button class="btn btn-success w-100 mt-3 fw-semibold" onclick="confirmSubmit()">
                        <i class="bi bi-check2-all me-2"></i>Submit Test
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Submit Confirmation Modal --}}
<div class="modal fade" id="submitModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check2-all me-2"></i>Submit Test</h5>
            </div>
            <div class="modal-body text-center p-4">
                <i class="bi bi-question-circle display-4 text-warning mb-3"></i>
                <h5>Are you sure you want to submit?</h5>
                <p class="text-muted">You cannot change answers after submission.</p>
                <div class="alert alert-info small">
                    Answered: <strong id="modal-answered">-</strong> |
                    Unanswered: <strong id="modal-unanswered">-</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Test</button>
                <button type="button" class="btn btn-success fw-bold" id="finalSubmitBtn" onclick="submitTest('Manual submission')">
                    <i class="bi bi-check2-all me-2"></i>Yes, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const ATTEMPT_ID = {{ $attempt->id }};
const CSRF = document.querySelector('meta[name=csrf-token]').content;
const TEST_MODE = '{{ $test->mode }}';
const ANTI_CHEAT = {{ $test->anti_cheat ? 'true' : 'false' }};
const VIOLATION_LIMIT = {{ $test->violation_limit ?? 3 }};
const TOTAL_Q = {{ $orderedQuestions->count() }};
let currentIndex = 0;
let violationCount = 0;
let remainingSeconds = {{ $remaining }};
let isSubmitting = false;
let stats = {
    answered: {{ $answers->whereNotNull('selected_option')->count() }},
    unanswered: {{ $answers->whereNull('selected_option')->count() }},
    review: {{ $answers->where('is_marked_review',true)->count() }}
};

// ========== TIMER ==========
function formatTime(s) {
    const h = Math.floor(s/3600), m = Math.floor((s%3600)/60), sec = s%60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
}

const timerEl = document.getElementById('timer-display');
const timerInterval = setInterval(() => {
    if (remainingSeconds <= 0) {
        clearInterval(timerInterval);
        submitTest('Time expired – Test auto-submitted.');
        return;
    }
    remainingSeconds--;
    timerEl.textContent = formatTime(remainingSeconds);
    if (remainingSeconds <= 300) timerEl.classList.add('danger-timer');
}, 1000);
timerEl.textContent = formatTime(remainingSeconds);

// ========== NAVIGATION (Mode A) ==========
function goTo(index) {
    if (index < 0 || index >= TOTAL_Q) return;
    document.querySelectorAll('.question-slide').forEach(s => s.classList.add('d-none'));
    document.getElementById('slide-' + index).classList.remove('d-none');
    // Update palette current highlight
    document.querySelectorAll('.palette-btn').forEach((b, i) => {
        b.classList.remove('q-current');
    });
    const palBtn = document.getElementById('pal-' + index);
    if (palBtn && !palBtn.classList.contains('q-answered') && !palBtn.classList.contains('q-review')) {
        palBtn.classList.add('q-current');
    }
    currentIndex = index;
}

// ========== SAVE ANSWER ==========
function saveAnswer(questionId, option, index) {
    fetch(`/student/attempt/${ATTEMPT_ID}/answer`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({question_id: questionId, selected_option: option})
    })
    .then(r => r.json())
    .then(() => updatePalette(index, 'answered'));
}

function clearAnswer(questionId, index) {
    document.querySelectorAll(`input[name="answer_${questionId}"]`).forEach(r => r.checked = false);
    fetch(`/student/attempt/${ATTEMPT_ID}/answer`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({question_id: questionId, selected_option: null})
    })
    .then(() => updatePalette(index, 'unanswered'));
}

function toggleReview(questionId, btn) {
    const isReview = btn.classList.toggle('active');
    btn.classList.toggle('btn-warning', isReview);
    btn.classList.toggle('btn-outline-warning', !isReview);
    fetch(`/student/attempt/${ATTEMPT_ID}/answer`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({question_id: questionId, is_marked_review: isReview})
    });
    // Find index
    const pal = document.querySelector('.palette-btn.q-current, .palette-btn.q-answered');
}

// ========== PALETTE UPDATE ==========
function updatePalette(index, status) {
    const btn = document.getElementById('pal-' + index);
    if (!btn) return;
    btn.classList.remove('q-answered','q-unanswered','q-review','q-current');
    btn.classList.add('q-' + status);
    updateStats();
}

function updateStats() {
    const answered = document.querySelectorAll('.palette-btn.q-answered').length;
    const review   = document.querySelectorAll('.palette-btn.q-review').length;
    const unanswered = TOTAL_Q - answered - review;
    document.getElementById('stat-answered').textContent = answered;
    document.getElementById('stat-unanswered').textContent = unanswered;
    document.getElementById('stat-review').textContent = review;
    document.getElementById('modal-answered').textContent = answered;
    document.getElementById('modal-unanswered').textContent = unanswered;
}

// ========== SUBMIT ==========
function confirmSubmit() {
    updateStats();
    new bootstrap.Modal(document.getElementById('submitModal')).show();
}

function submitTest(reason) {
    if (isSubmitting) return;
    isSubmitting = true;
    clearInterval(timerInterval);
    document.getElementById('finalSubmitBtn').disabled = true;
    document.getElementById('finalSubmitBtn').innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';

    fetch(`/student/attempt/${ATTEMPT_ID}/submit`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({reason})
    })
    .then(r => r.json())
    .then(data => {
        if (data.redirect) window.location.href = data.redirect;
    })
    .catch(() => { isSubmitting = false; });
}

// ========== ANTI-CHEAT ==========
let dismissCallback = null;

function showViolation(type, title, message) {
    if (!ANTI_CHEAT) return;
    document.getElementById('violationTitle').textContent = title;
    document.getElementById('violationMsg').textContent = message;
    document.getElementById('violationOverlay').style.display = 'flex';

    fetch(`/student/attempt/${ATTEMPT_ID}/violation`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({violation_type: type})
    })
    .then(r => r.json())
    .then(data => {
        violationCount = data.warning_number;
        if (data.auto_submitted) {
            document.getElementById('violationTitle').textContent = '🚫 Test Auto-Submitted';
            document.getElementById('violationMsg').textContent = 'You have exceeded the violation limit. Test submitted.';
            document.getElementById('violationDismiss').style.display = 'none';
            setTimeout(() => window.location.href = `/student/result/${ATTEMPT_ID}`, 3000);
        } else {
            document.getElementById('violationMsg').textContent =
                `${message}\n\nWarning ${data.warning_number} of ${VIOLATION_LIMIT}. ${data.remaining} warning(s) remaining before auto-submit.`;
        }
    });
}

function dismissViolation() {
    document.getElementById('violationOverlay').style.display = 'none';
}

if (ANTI_CHEAT) {
    // Tab switch
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) showViolation('tab_switch', '⚠️ WARNING: Tab Switch Detected!', 'You switched or minimized the browser tab.');
    });
    // Window blur
    window.addEventListener('blur', () => {
        showViolation('window_minimize', '⚠️ WARNING: Window Focus Lost!', 'You minimized or switched the window.');
    });
    // Copy attempt
    document.addEventListener('copy', (e) => {
        e.preventDefault();
        showViolation('copy_attempt', '⚠️ WARNING: Copy Attempt Detected!', 'Copying content is not allowed during the exam.');
    });
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && (e.key==='c'||e.key==='v'||e.key==='u'||e.key==='p')) {
            e.preventDefault();
            showViolation('ctrl_key', '⚠️ WARNING: Shortcut Blocked!', 'Keyboard shortcuts are disabled during the exam.');
        }
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey)) e.preventDefault();
    });
    // Right-click block
    document.addEventListener('contextmenu', (e) => e.preventDefault());
    // Prevent navigation away
    window.addEventListener('beforeunload', (e) => {
        if (!isSubmitting) { e.preventDefault(); e.returnValue = ''; }
    });
}
</script>
</body>
</html>