{{--
====================================================================
FILE PATH: resources/views/student/test-engine.blade.php
====================================================================
--}}
<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ auth()->user()->dark_mode ? 'dark' : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->title }} — SWF Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ─── BASE ─────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            user-select: none;
            -webkit-user-select: none;
            background: var(--bs-body-bg);
            overflow-x: hidden;
        }

        /* ─── TOP EXAM BAR ──────────────────────────────────── */
        .exam-topbar {
            background: linear-gradient(90deg, #1e40af 0%, #1d4ed8 100%);
            color: #fff;
            padding: 0.6rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 2px 16px rgba(30,64,175,.35);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .exam-topbar .test-title {
            font-weight: 700;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }
        .exam-topbar .student-meta { font-size: .75rem; opacity: .75; }

        /* ─── TIMER ─────────────────────────────────────────── */
        .timer-wrap {
            background: rgba(0,0,0,.25);
            border-radius: 10px;
            padding: .35rem 1rem;
            text-align: center;
            min-width: 130px;
        }
        .timer-label { font-size: .65rem; text-transform: uppercase; letter-spacing: 1px; opacity: .7; }
        #timerDisplay {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: 3px;
            font-variant-numeric: tabular-nums;
            transition: color .3s;
        }
        #timerDisplay.warning  { color: #fbbf24; }
        #timerDisplay.critical { color: #f87171; animation: timerPulse .7s infinite; }
        @keyframes timerPulse { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* ─── MAIN LAYOUT ───────────────────────────────────── */
        .exam-body { display: flex; min-height: calc(100vh - 58px); }
        .question-area { flex: 1; padding: 1.5rem; max-width: 820px; margin: 0 auto; }
        .palette-col {
            width: 280px;
            flex-shrink: 0;
            padding: 1rem;
            border-left: 1px solid var(--bs-border-color);
            position: sticky;
            top: 58px;
            height: calc(100vh - 58px);
            overflow-y: auto;
        }

        /* ─── SECTION BADGE ─────────────────────────────────── */
        .section-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.5rem 0 1rem;
        }
        .section-divider hr { flex: 1; margin: 0; border-color: var(--bs-primary); opacity: .25; }
        .section-badge {
            background: linear-gradient(135deg,#1e40af,#0284c7);
            color: #fff;
            padding: .3rem .9rem;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        /* ─── QUESTION CARD ─────────────────────────────────── */
        .q-card {
            border: 1.5px solid var(--bs-border-color);
            border-radius: 14px;
            background: var(--bs-body-bg);
            overflow: hidden;
            transition: box-shadow .25s;
        }
        .q-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.08); }
        .q-card-header {
            background: linear-gradient(90deg,rgba(30,64,175,.07) 0%,transparent 100%);
            padding: .75rem 1.25rem;
            border-bottom: 1px solid var(--bs-border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .q-number-badge {
            background: #1e40af;
            color: #fff;
            border-radius: 8px;
            padding: .2rem .65rem;
            font-size: .78rem;
            font-weight: 700;
        }
        .marks-badge {
            background: #f59e0b22;
            color: #b45309;
            border: 1px solid #f59e0b55;
            border-radius: 6px;
            padding: .15rem .55rem;
            font-size: .75rem;
            font-weight: 600;
        }
        [data-bs-theme="dark"] .marks-badge { color:#fbbf24; }
        .q-card-body { padding: 1.25rem; }
        .q-statement { font-size: 1rem; font-weight: 500; line-height: 1.65; margin-bottom: 1.1rem; }

        /* ─── MCQ OPTIONS ────────────────────────────────────── */
        .option-item { margin-bottom: .55rem; }
        .option-item input[type="radio"] { display: none; }
        .option-label {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: .75rem 1rem;
            border: 2px solid var(--bs-border-color);
            border-radius: 10px;
            cursor: pointer;
            transition: all .18s;
            line-height: 1.5;
        }
        .option-label:hover {
            border-color: #3b82f6;
            background: rgba(59,130,246,.06);
        }
        .option-item input[type="radio"]:checked + .option-label {
            border-color: #1e40af;
            background: rgba(30,64,175,.09);
            font-weight: 600;
        }
        [data-bs-theme="dark"] .option-label:hover { background: rgba(59,130,246,.12); }
        [data-bs-theme="dark"] .option-item input[type="radio"]:checked + .option-label { background: rgba(30,64,175,.25); }

        .option-key {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 800;
            flex-shrink: 0;
            transition: all .18s;
        }
        [data-bs-theme="dark"] .option-key { background:#374151; color:#d1d5db; }
        .option-item input[type="radio"]:checked + .option-label .option-key {
            background: #1e40af;
            color: #fff;
        }

        /* ─── MODE A NAV BUTTONS ────────────────────────────── */
        .nav-bar-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--bs-border-color);
            gap: .5rem;
        }
        .btn-clear-ans {
            font-size: .8rem;
            padding: .3rem .75rem;
            border-radius: 20px;
        }

        /* ─── PALETTE ────────────────────────────────────────── */
        .palette-title {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--bs-secondary-color);
            margin-bottom: .75rem;
        }
        .palette-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
            margin-bottom: 1rem;
        }
        .pal-btn {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 7px;
            border: none;
            font-size: .72rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .12s, opacity .12s;
            outline: none;
        }
        .pal-btn:hover { transform: scale(1.15); }
        .pal-btn.state-unanswered { background: #e5e7eb; color: #6b7280; }
        .pal-btn.state-answered   { background: #059669; color: #fff; }
        .pal-btn.state-review     { background: #f59e0b; color: #fff; }
        .pal-btn.state-current    { background: #1e40af; color: #fff; box-shadow: 0 0 0 3px rgba(30,64,175,.35); }
        [data-bs-theme="dark"] .pal-btn.state-unanswered { background:#374151; color:#9ca3af; }

        /* ─── LEGEND ─────────────────────────────────────────── */
        .legend-dot {
            width: 11px;
            height: 11px;
            border-radius: 3px;
            display: inline-block;
            flex-shrink: 0;
        }

        /* ─── PALETTE STATS ─────────────────────────────────── */
        .stat-row { display:flex; justify-content:space-between; font-size:.82rem; padding:.3rem 0; border-bottom:1px solid var(--bs-border-color); }
        .stat-row:last-child { border:none; }

        /* ─── MARK-FOR-REVIEW TOGGLE ────────────────────────── */
        .btn-review { font-size: .78rem; }

        /* ─── MOBILE RESPONSIVE ─────────────────────────────── */
        @media(max-width:991px) {
            .palette-col { display: none; }
            .question-area { max-width: 100%; }
        }
        .mobile-palette-toggle {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 16px;
            z-index: 999;
        }
        @media(max-width:991px) { .mobile-palette-toggle { display: block; } }

        /* ─── SUBMIT BAR ─────────────────────────────────────── */
        .submit-bar {
            position: sticky;
            bottom: 0;
            background: var(--bs-body-bg);
            border-top: 2px solid var(--bs-border-color);
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
        }

        /* ─── VIOLATION OVERLAY ─────────────────────────────── */
        #violationOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #violationOverlay.show-overlay { display: flex; }
        .violation-box {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            max-width: 440px;
            width: 90%;
            text-align: center;
            animation: popIn .3s ease;
        }
        [data-bs-theme="dark"] .violation-box { background: #1e293b; }
        @keyframes popIn { from{transform:scale(.8);opacity:0} to{transform:scale(1);opacity:1} }
        .violation-progress { height: 8px; border-radius: 4px; overflow: hidden; background: #e5e7eb; margin: 1rem 0; }
        .violation-progress-bar { height: 100%; border-radius: 4px; background: linear-gradient(90deg,#f59e0b,#dc2626); transition: width .5s; }

        /* ─── PRINT ──────────────────────────────────────────── */
        @media print { .exam-topbar,.palette-col,.submit-bar,.nav-bar-bottom,.no-print { display:none!important; } }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════
     HIDDEN FORM (auto-submit carrier)
══════════════════════════════════════════════════════════ --}}
<form id="examForm" method="POST" action="{{ route('student.test.submit', $attempt) }}" style="display:none">
    @csrf
    <input type="hidden" name="submission_reason" id="submissionReason" value="Manual submission by student.">
    <input type="hidden" name="violation_data"    id="violationData"    value="[]">
    <input type="hidden" name="time_taken"        id="timeTaken"        value="0">
</form>

{{-- ═══════════════════════════════════════════════════════
     VIOLATION WARNING OVERLAY
══════════════════════════════════════════════════════════ --}}
<div id="violationOverlay">
    <div class="violation-box shadow-lg">
        <div id="violOverlayIcon" class="mb-3" style="font-size:3rem">⚠️</div>
        <h5 id="violOverlayTitle" class="fw-bold mb-1">Exam Security Warning</h5>
        <p id="violOverlayMsg" class="text-muted mb-2" style="font-size:.9rem"></p>
        <div class="violation-progress">
            <div class="violation-progress-bar" id="violProgressBar" style="width:33%"></div>
        </div>
        <p id="violCounterText" class="fw-semibold mb-3" style="font-size:.85rem; color:#dc2626"></p>
        <button id="violDismissBtn" class="btn btn-primary px-4 no-print" onclick="dismissViolationOverlay()">
            <i class="bi bi-arrow-return-left me-2"></i>Return to Exam
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     EXAM TOP BAR
══════════════════════════════════════════════════════════ --}}
<div class="exam-topbar">
    <div>
        <div class="test-title">{{ $test->title }}</div>
        <div class="student-meta">
            {{ auth()->user()->name }}
            @if(auth()->user()->roll_number)| Roll: {{ auth()->user()->roll_number }}@endif
        </div>
    </div>

    <div class="timer-wrap">
        <div class="timer-label">Time Remaining</div>
        <div id="timerDisplay">--:--:--</div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-white text-primary fw-bold">
            Mode {{ $test->mode === 'A' ? 'A' : 'B' }}
        </span>
        <button class="btn btn-warning btn-sm fw-semibold px-3 no-print" onclick="triggerSubmitConfirm()">
            <i class="bi bi-check2-all me-1"></i>Submit
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MAIN EXAM BODY
══════════════════════════════════════════════════════════ --}}
<div class="exam-body">

    {{-- ─── QUESTION AREA ──────────────────────── --}}
    <div class="question-area">

        @if($test->mode === 'A')
        {{-- ╔══════════════════════════════╗
             ║  MODE A — ONE AT A TIME      ║
             ╚══════════════════════════════╝ --}}
        @php $globalIndex = 0; $allQuestions = collect(); @endphp
        @foreach($test->sections as $section)
            @foreach($section->activeQuestions as $q)
                @php $allQuestions->push(['question'=>$q,'section'=>$section,'gi'=>$globalIndex]); $globalIndex++; @endphp
            @endforeach
        @endforeach

        @foreach($allQuestions as $item)
        @php $q = $item['question']; $gi = $item['gi']; $sec = $item['section']; $total = $allQuestions->count(); @endphp
        <div class="q-slide {{ $gi === 0 ? '' : 'd-none' }}"
             id="slide-{{ $gi }}"
             data-gi="{{ $gi }}"
             data-qid="{{ $q->id }}">

            {{-- Section label --}}
            <div class="section-divider">
                <hr><span class="section-badge"><i class="bi bi-layers me-1"></i>{{ $sec->title }}</span><hr>
            </div>

            <div class="q-card shadow-sm">
                <div class="q-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="q-number-badge">Q {{ $gi + 1 }}</span>
                        <small class="text-muted">of {{ $total }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="marks-badge">
                            <i class="bi bi-star-fill me-1" style="font-size:.6rem"></i>{{ $q->marks }} Mark{{ $q->marks != 1 ? 's' : '' }}
                        </span>
                        <button type="button"
                                class="btn btn-sm btn-outline-warning btn-review"
                                id="reviewBtn-{{ $gi }}"
                                onclick="toggleReview({{ $gi }}, {{ $q->id }})">
                            <i class="bi bi-flag me-1"></i>Mark Review
                        </button>
                    </div>
                </div>

                <div class="q-card-body">
                    <p class="q-statement">{{ $q->statement }}</p>
                    <div class="options-list">
                        @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $key => $label)
                        <div class="option-item">
                            <input type="radio"
                                   name="q{{ $q->id }}"
                                   id="q{{ $q->id }}_{{ $key }}"
                                   value="{{ $key }}"
                                   data-gi="{{ $gi }}"
                                   data-qid="{{ $q->id }}"
                                   onchange="handleAnswer({{ $gi }}, {{ $q->id }}, '{{ $key }}')"
                                   {{ isset($answers[$q->id]) && $answers[$q->id] === $key ? 'checked' : '' }}>
                            <label class="option-label" for="q{{ $q->id }}_{{ $key }}">
                                <span class="option-key">{{ $label }}</span>
                                <span>{{ $q->{'option_'.$key} }}</span>
                            </label>
                        </div>
                        @endforeach
                        @if($q->option_e)
                        <div class="option-item">
                            <input type="radio"
                                   name="q{{ $q->id }}"
                                   id="q{{ $q->id }}_e"
                                   value="e"
                                   data-gi="{{ $gi }}"
                                   data-qid="{{ $q->id }}"
                                   onchange="handleAnswer({{ $gi }}, {{ $q->id }}, 'e')"
                                   {{ isset($answers[$q->id]) && $answers[$q->id] === 'e' ? 'checked' : '' }}>
                            <label class="option-label" for="q{{ $q->id }}_e">
                                <span class="option-key">E</span>
                                <span>{{ $q->option_e }}</span>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="nav-bar-bottom">
                    <button class="btn btn-outline-secondary btn-sm px-3"
                            onclick="goToQuestion({{ $gi - 1 }})"
                            {{ $gi === 0 ? 'disabled' : '' }}>
                        <i class="bi bi-arrow-left me-1"></i>Previous
                    </button>
                    <button class="btn btn-outline-danger btn-clear-ans btn-sm"
                            onclick="clearAnswer({{ $gi }}, {{ $q->id }})">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </button>
                    @if($gi < $total - 1)
                    <button class="btn btn-primary btn-sm px-3"
                            onclick="goToQuestion({{ $gi + 1 }})">
                        Next <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                    @else
                    <button class="btn btn-success btn-sm fw-bold px-3"
                            onclick="triggerSubmitConfirm()">
                        <i class="bi bi-check2-all me-1"></i>Finish
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        @else
        {{-- ╔══════════════════════════════╗
             ║  MODE B — ALL VISIBLE        ║
             ╚══════════════════════════════╝ --}}
        @php $globalIndex = 0; @endphp
        @foreach($test->sections as $section)
        <div class="section-divider">
            <hr><span class="section-badge"><i class="bi bi-layers me-1"></i>{{ $section->title }}</span><hr>
        </div>
        @foreach($section->activeQuestions as $q)
        @php $gi = $globalIndex++; @endphp
        <div class="q-card shadow-sm mb-3"
             id="slide-{{ $gi }}"
             data-gi="{{ $gi }}"
             data-qid="{{ $q->id }}">
            <div class="q-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="q-number-badge">Q {{ $gi + 1 }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="marks-badge"><i class="bi bi-star-fill me-1" style="font-size:.6rem"></i>{{ $q->marks }} Mark{{ $q->marks != 1 ? 's' : '' }}</span>
                    <button type="button"
                            class="btn btn-sm btn-outline-warning btn-review"
                            id="reviewBtn-{{ $gi }}"
                            onclick="toggleReview({{ $gi }}, {{ $q->id }})">
                        <i class="bi bi-flag me-1"></i>Review
                    </button>
                </div>
            </div>
            <div class="q-card-body">
                <p class="q-statement">{{ $q->statement }}</p>
                @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $key => $label)
                <div class="option-item">
                    <input type="radio"
                           name="q{{ $q->id }}"
                           id="q{{ $q->id }}_{{ $key }}"
                           value="{{ $key }}"
                           data-gi="{{ $gi }}"
                           data-qid="{{ $q->id }}"
                           onchange="handleAnswer({{ $gi }}, {{ $q->id }}, '{{ $key }}')"
                           {{ isset($answers[$q->id]) && $answers[$q->id] === $key ? 'checked' : '' }}>
                    <label class="option-label" for="q{{ $q->id }}_{{ $key }}">
                        <span class="option-key">{{ $label }}</span>
                        <span>{{ $q->{'option_'.$key} }}</span>
                    </label>
                </div>
                @endforeach
                @if($q->option_e)
                <div class="option-item">
                    <input type="radio"
                           name="q{{ $q->id }}"
                           id="q{{ $q->id }}_e"
                           value="e"
                           data-gi="{{ $gi }}"
                           data-qid="{{ $q->id }}"
                           onchange="handleAnswer({{ $gi }}, {{ $q->id }}, 'e')"
                           {{ isset($answers[$q->id]) && $answers[$q->id] === 'e' ? 'checked' : '' }}>
                    <label class="option-label" for="q{{ $q->id }}_e">
                        <span class="option-key">E</span>
                        <span>{{ $q->option_e }}</span>
                    </label>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endforeach

        <div class="text-center py-4">
            <button class="btn btn-success btn-lg fw-bold px-5 shadow" onclick="triggerSubmitConfirm()">
                <i class="bi bi-check2-all me-2"></i>Submit Test
            </button>
        </div>
        @endif

    </div>{{-- /question-area --}}

    {{-- ─── PALETTE SIDEBAR ─────────────────────── --}}
    <aside class="palette-col no-print">
        <div class="palette-title">Question Palette</div>

        {{-- Legend --}}
        <div class="d-flex flex-wrap gap-2 mb-3" style="font-size:.72rem">
            <span class="d-flex align-items-center gap-1"><span class="legend-dot" style="background:#1e40af"></span>Current</span>
            <span class="d-flex align-items-center gap-1"><span class="legend-dot" style="background:#059669"></span>Answered</span>
            <span class="d-flex align-items-center gap-1"><span class="legend-dot" style="background:#f59e0b"></span>Review</span>
            <span class="d-flex align-items-center gap-1"><span class="legend-dot" style="background:#e5e7eb"></span>Skipped</span>
        </div>

        <div class="palette-grid" id="paletteGrid">
            @php $gi2 = 0; @endphp
            @foreach($test->sections as $sec)
                @foreach($sec->activeQuestions as $q)
                @php
                    $state = 'state-unanswered';
                    if (isset($answers[$q->id])) $state = 'state-answered';
                    if ($gi2 === 0 && $test->mode === 'A') $state = 'state-current';
                @endphp
                <button class="pal-btn {{ $state }}"
                        id="palBtn-{{ $gi2 }}"
                        data-gi="{{ $gi2 }}"
                        onclick="palJump({{ $gi2 }})"
                        title="Q{{ $gi2+1 }}">
                    {{ $gi2 + 1 }}
                </button>
                @php $gi2++; @endphp
                @endforeach
            @endforeach
        </div>

        {{-- Stats --}}
        <div class="mb-3">
            <div class="stat-row">
                <span>Total</span>
                <strong>{{ $gi2 }}</strong>
            </div>
            <div class="stat-row">
                <span>✅ Answered</span>
                <strong id="statAnswered" class="text-success">{{ collect($answers)->filter()->count() }}</strong>
            </div>
            <div class="stat-row">
                <span>⬜ Unanswered</span>
                <strong id="statUnanswered" class="text-danger">{{ $gi2 - collect($answers)->filter()->count() }}</strong>
            </div>
            <div class="stat-row">
                <span>🚩 For Review</span>
                <strong id="statReview" class="text-warning">0</strong>
            </div>
        </div>

        <div class="d-grid">
            <button class="btn btn-success fw-bold" onclick="triggerSubmitConfirm()">
                <i class="bi bi-check2-all me-1"></i>Submit Test
            </button>
        </div>
    </aside>

</div>{{-- /exam-body --}}

{{-- Mobile Palette Toggle --}}
<button class="btn btn-primary rounded-circle shadow mobile-palette-toggle no-print"
        style="width:52px;height:52px;font-size:1.3rem"
        data-bs-toggle="offcanvas"
        data-bs-target="#mobilePalette">
    <i class="bi bi-grid-3x3"></i>
</button>

{{-- Mobile Offcanvas Palette --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobilePalette" style="width:290px">
    <div class="offcanvas-header">
        <h6 class="offcanvas-title fw-bold">Question Palette</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" id="mobilePaletteBody">
        {{-- dynamically cloned by JS --}}
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     SUBMIT CONFIRMATION MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="submitModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden">
            <div class="modal-header" style="background:linear-gradient(90deg,#059669,#047857);color:#fff;border:none">
                <h5 class="modal-title fw-bold"><i class="bi bi-check2-circle me-2"></i>Submit Exam</h5>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="fs-1 mb-2">📋</div>
                <h5 class="fw-bold mb-1">Ready to submit?</h5>
                <p class="text-muted mb-3" style="font-size:.9rem">You cannot change answers after submitting.</p>
                <div class="d-flex justify-content-center gap-4 mb-1">
                    <div class="text-center">
                        <div class="fs-3 fw-bold text-success" id="modalAnswered">—</div>
                        <div class="small text-muted">Answered</div>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold text-danger" id="modalUnanswered">—</div>
                        <div class="small text-muted">Unanswered</div>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold text-warning" id="modalReview">—</div>
                        <div class="small text-muted">Review</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-return-left me-1"></i>Continue Exam
                </button>
                <button type="button" class="btn btn-success fw-bold px-4" id="finalSubmitBtn" onclick="finalSubmit()">
                    <i class="bi bi-check2-all me-1"></i>Submit Now
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     JAVASCRIPT — EXAM ENGINE + ANTI-CHEAT
══════════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
"use strict";
/* ═══════════════════════════════════════════════════
   CONFIG
═══════════════════════════════════════════════════ */
const EXAM_CONFIG = {
    attemptId:       {{ $attempt->id }},
    testMode:        '{{ $test->mode }}',
    totalSeconds:    {{ $remaining }},           // seconds left passed from controller
    antiCheat:       {{ $test->anti_cheat ? 'true' : 'false' }},
    violationLimit:  {{ $test->violation_limit ?? 3 }},
    csrfToken:       document.querySelector('meta[name="csrf-token"]').content,
    saveAnswerUrl:   '{{ route("student.test.answer", $attempt) }}',
    violationUrl:    '{{ route("student.test.violation", $attempt) }}',
    submitUrl:       '{{ route("student.test.submit", $attempt) }}',
};

const TOTAL_Q = {{ $gi2 ?? $allQuestions?->count() ?? 0 }};

/* ═══════════════════════════════════════════════════
   STATE
═══════════════════════════════════════════════════ */
let state = {
    currentGi:       0,
    answers:         {},   // gi -> option key
    reviews:         {},   // gi -> bool
    violations: {
        count:       0,
        log:         [],   // [{type, timestamp}]
    },
    secondsLeft:     EXAM_CONFIG.totalSeconds,
    secondsElapsed:  0,
    isSubmitting:    false,
    timerHandle:     null,
};

/* Pre-fill existing answers from Blade */
@foreach($answers as $qId => $option)
    @if($option)
    state.answers[/* gi lookup done below by qid map */0] = '{{ $option }}';
    @endif
@endforeach

/* Build qid→gi map */
const qidToGi = {};
document.querySelectorAll('[data-qid][data-gi]').forEach(el => {
    const qid = el.dataset.qid;
    const gi  = parseInt(el.dataset.gi);
    if (!qidToGi[qid]) qidToGi[qid] = gi;
});
// Re-map answers properly
@foreach($answers as $qId => $option)
    @if($option)
    (function(){ const gi = qidToGi[{{ $qId }}]; if(gi !== undefined) state.answers[gi] = '{{ $option }}'; })();
    @endif
@endforeach

/* ═══════════════════════════════════════════════════
   ① COUNTDOWN TIMER
═══════════════════════════════════════════════════ */
const timerEl = document.getElementById('timerDisplay');

function formatHMS(s) {
    const h   = Math.floor(s / 3600);
    const m   = Math.floor((s % 3600) / 60);
    const sec = s % 60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
}

function startTimer() {
    timerEl.textContent = formatHMS(state.secondsLeft);
    state.timerHandle = setInterval(() => {
        if (state.secondsLeft <= 0) {
            clearInterval(state.timerHandle);
            autoSubmitTimeUp();
            return;
        }
        state.secondsLeft--;
        state.secondsElapsed++;
        timerEl.textContent = formatHMS(state.secondsLeft);
        if (state.secondsLeft <= 300 && state.secondsLeft > 60) timerEl.className = 'warning';
        else if (state.secondsLeft <= 60) timerEl.className = 'critical';
    }, 1000);
}
startTimer();

/* ═══════════════════════════════════════════════════
   ② QUESTION NAVIGATION (Mode A)
═══════════════════════════════════════════════════ */
function goToQuestion(gi) {
    if (gi < 0 || gi >= TOTAL_Q) return;
    // Hide current
    const current = document.getElementById('slide-' + state.currentGi);
    if (current) current.classList.add('d-none');
    // Update palette
    updatePalBtn(state.currentGi);
    // Show new
    const next = document.getElementById('slide-' + gi);
    if (next) next.classList.remove('d-none');
    state.currentGi = gi;
    updatePalBtn(gi, true);
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function palJump(gi) {
    if (EXAM_CONFIG.testMode === 'A') {
        goToQuestion(gi);
    } else {
        const el = document.getElementById('slide-' + gi);
        if (el) el.scrollIntoView({behavior:'smooth', block:'start'});
    }
    // Close mobile offcanvas if open
    const oc = document.getElementById('mobilePalette');
    if (oc) bootstrap.Offcanvas.getInstance(oc)?.hide();
}

/* ═══════════════════════════════════════════════════
   ③ HANDLE ANSWERS
═══════════════════════════════════════════════════ */
function handleAnswer(gi, qid, option) {
    state.answers[gi] = option;
    updatePalBtn(gi, gi === state.currentGi);
    updateStats();
    // Persist to server (debounced)
    persistAnswer(qid, option, false);
}

function clearAnswer(gi, qid) {
    delete state.answers[gi];
    // Uncheck radios
    document.querySelectorAll(`input[name="q${qid}"]`).forEach(r => r.checked = false);
    updatePalBtn(gi, gi === state.currentGi);
    updateStats();
    persistAnswer(qid, null, false);
}

let answerDebounces = {};
function persistAnswer(qid, option, isReview) {
    clearTimeout(answerDebounces[qid]);
    answerDebounces[qid] = setTimeout(() => {
        fetch(EXAM_CONFIG.saveAnswerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': EXAM_CONFIG.csrfToken,
            },
            body: JSON.stringify({
                question_id:      qid,
                selected_option:  option,
                is_marked_review: isReview,
            }),
        }).catch(() => {});
    }, 400);
}

/* ═══════════════════════════════════════════════════
   ④ MARK FOR REVIEW
═══════════════════════════════════════════════════ */
function toggleReview(gi, qid) {
    state.reviews[gi] = !state.reviews[gi];
    const btn = document.getElementById('reviewBtn-' + gi);
    if (state.reviews[gi]) {
        btn?.classList.replace('btn-outline-warning', 'btn-warning');
    } else {
        btn?.classList.replace('btn-warning', 'btn-outline-warning');
    }
    updatePalBtn(gi, gi === state.currentGi);
    updateStats();
    persistAnswer(qid, state.answers[gi] || null, state.reviews[gi]);
}

/* ═══════════════════════════════════════════════════
   ⑤ PALETTE
═══════════════════════════════════════════════════ */
function updatePalBtn(gi, isCurrent = false) {
    const btn = document.getElementById('palBtn-' + gi);
    if (!btn) return;
    btn.className = 'pal-btn';
    if (isCurrent && EXAM_CONFIG.testMode === 'A') {
        btn.classList.add('state-current');
    } else if (state.reviews[gi]) {
        btn.classList.add('state-review');
    } else if (state.answers[gi]) {
        btn.classList.add('state-answered');
    } else {
        btn.classList.add('state-unanswered');
    }
}

function updateStats() {
    const answered   = Object.keys(state.answers).filter(k => state.answers[k]).length;
    const reviewed   = Object.values(state.reviews).filter(Boolean).length;
    const unanswered = TOTAL_Q - answered;

    document.getElementById('statAnswered').textContent   = answered;
    document.getElementById('statUnanswered').textContent = unanswered;
    document.getElementById('statReview').textContent     = reviewed;
    document.getElementById('modalAnswered').textContent   = answered;
    document.getElementById('modalUnanswered').textContent = unanswered;
    document.getElementById('modalReview').textContent     = reviewed;
}
updateStats();

/* Mobile palette mirror */
document.getElementById('mobilePalette')?.addEventListener('show.bs.offcanvas', () => {
    const body = document.getElementById('mobilePaletteBody');
    const grid = document.getElementById('paletteGrid');
    if (body && grid) body.innerHTML = grid.outerHTML;
});

/* ═══════════════════════════════════════════════════
   ⑥ SUBMIT FLOW
═══════════════════════════════════════════════════ */
function triggerSubmitConfirm() {
    updateStats();
    new bootstrap.Modal(document.getElementById('submitModal')).show();
}

function finalSubmit(reason) {
    if (state.isSubmitting) return;
    state.isSubmitting = true;
    clearInterval(state.timerHandle);

    const btn = document.getElementById('finalSubmitBtn');
    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting…'; }

    const submissionReason = reason || 'Manual submission by student.';

    document.getElementById('submissionReason').value = submissionReason;
    document.getElementById('violationData').value    = JSON.stringify(state.violations.log);
    document.getElementById('timeTaken').value        = state.secondsElapsed;

    // Use fetch for clean JSON submission
    fetch(EXAM_CONFIG.submitUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': EXAM_CONFIG.csrfToken,
        },
        body: JSON.stringify({
            reason:          submissionReason,
            violation_data:  state.violations.log,
            time_taken:      state.secondsElapsed,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.redirect) window.location.href = data.redirect;
        else document.getElementById('examForm').submit();
    })
    .catch(() => document.getElementById('examForm').submit());
}

function autoSubmitTimeUp() {
    finalSubmit('Time expired – Test auto-submitted.');
}

/* ═══════════════════════════════════════════════════
   ⑦ ANTI-CHEAT ENGINE
═══════════════════════════════════════════════════ */
if (EXAM_CONFIG.antiCheat) {

    /* — Block right-click — */
    document.addEventListener('contextmenu', e => e.preventDefault());

    /* — Block copy / cut / paste — */
    document.addEventListener('copy',  e => e.preventDefault());
    document.addEventListener('cut',   e => e.preventDefault());
    document.addEventListener('paste', e => e.preventDefault());

    /* — Block keyboard shortcuts — */
    document.addEventListener('keydown', e => {
        const blocked = (e.ctrlKey || e.metaKey) && ['c','C','u','U','s','S','p','P','a','A','v','V'].includes(e.key);
        if (blocked || e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
            e.preventDefault();
            if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'C')) {
                recordViolation('copy_attempt');
            }
        }
    });

    /* — Tab / Window Blur Detection — */
    let blurCooldown = false;

    function onBlur() {
        if (blurCooldown || state.isSubmitting) return;
        blurCooldown = true;
        setTimeout(() => blurCooldown = false, 3000);
        recordViolation('tab_switch');
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) onBlur();
    });
    window.addEventListener('blur', onBlur);

    /* — Violation Recorder — */
    function recordViolation(type) {
        state.violations.count++;
        const entry = { type, timestamp: new Date().toISOString(), count: state.violations.count };
        state.violations.log.push(entry);

        // Log to server async
        fetch(EXAM_CONFIG.violationUrl, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': EXAM_CONFIG.csrfToken },
            body: JSON.stringify(entry),
        }).catch(() => {});

        const limit = EXAM_CONFIG.violationLimit;

        if (state.violations.count >= limit) {
            showViolationOverlay(type, limit, true);
        } else {
            showViolationOverlay(type, limit, false);
        }
    }

    /* — Violation Overlay — */
    function showViolationOverlay(type, limit, isAutoSubmit) {
        const count    = state.violations.count;
        const pct      = Math.min((count / limit) * 100, 100);
        const overlay  = document.getElementById('violationOverlay');
        const icon     = document.getElementById('violOverlayIcon');
        const title    = document.getElementById('violOverlayTitle');
        const msg      = document.getElementById('violOverlayMsg');
        const counter  = document.getElementById('violCounterText');
        const bar      = document.getElementById('violProgressBar');
        const dismissBtn = document.getElementById('violDismissBtn');

        const typeLabels = {
            tab_switch:   'Tab Switching / Window Blur',
            copy_attempt: 'Copy Attempt (Ctrl+C)',
            right_click:  'Right-Click Attempt',
        };

        bar.style.width = pct + '%';
        overlay.classList.add('show-overlay');

        if (isAutoSubmit) {
            icon.textContent  = '🚫';
            title.textContent = 'Exam Auto-Submitted!';
            msg.textContent   = `You have reached the maximum number of violations (${limit}/${limit}). Your exam has been automatically submitted.`;
            counter.textContent = 'Violation: ' + (typeLabels[type] || type);
            dismissBtn.style.display = 'none';
            // auto submit after 3s
            setTimeout(() => finalSubmit('Test auto-submitted due to repeated exam rule violations (tab switching/copy attempt).'), 3000);
        } else {
            icon.textContent  = '⚠️';
            title.textContent = `Security Warning — ${count} of ${limit}`;
            msg.textContent   = `Detected: ${typeLabels[type] || type}. Please stay on this page and do not attempt to copy or switch tabs.`;
            counter.textContent = `Warning ${count} of ${limit}. ${limit - count} more violation(s) will auto-submit your exam.`;
            dismissBtn.style.display = '';
        }
    }

    function dismissViolationOverlay() {
        document.getElementById('violationOverlay').classList.remove('show-overlay');
    }
    window.dismissViolationOverlay = dismissViolationOverlay;

    /* — Prevent accidental back/close — */
    window.addEventListener('beforeunload', e => {
        if (!state.isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Your exam is in progress. Are you sure you want to leave?';
        }
    });
}

/* ═══════════════════════════════════════════════════
   INIT — Mode A set first q current
═══════════════════════════════════════════════════ */
if (EXAM_CONFIG.testMode === 'A') {
    updatePalBtn(0, true);
}
</script>
</body>
</html>