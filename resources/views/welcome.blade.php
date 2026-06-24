{{-- FILE PATH: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SWF PORTAL — Student Welfare Foundation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── NAVBAR ──────────────────────────────── */
        .navbar-brand .brand-mark {
            width:36px;height:36px;border-radius:9px;
            background:linear-gradient(135deg,#1e40af,#1d4ed8);
            color:#fff;font-weight:900;font-size:.8rem;
            display:flex;align-items:center;justify-content:center;
            flex-shrink:0;box-shadow:0 2px 8px rgba(30,64,175,.3);
        }
        .navbar-brand .brand-name { font-weight:800;color:#1e40af;font-size:1rem; }

        /* ── HERO ────────────────────────────────── */
        .hero-section {
            background:linear-gradient(135deg,#1e40af 0%,#0369a1 50%,#059669 100%);
            padding:60px 0;
        }
        @media(max-width:575px){ .hero-section { padding:40px 0 30px; } }
        .hero-title { font-size:clamp(1.75rem,5vw,3rem);font-weight:800;line-height:1.15; }
        .hero-sub   { font-size:clamp(.9rem,2.5vw,1.1rem);opacity:.8; }

        .stat-mini {
            background:rgba(255,255,255,.12);border-radius:12px;
            padding:.9rem .75rem;text-align:center;backdrop-filter:blur(4px);
        }
        .stat-mini .val { font-size:1.35rem;font-weight:800; }
        .stat-mini .lbl { font-size:.7rem;opacity:.75; }

        /* ── FEATURES ────────────────────────────── */
        .feature-card {
            border:none;border-radius:16px;
            transition:transform .3s,box-shadow .3s;
        }
        .feature-card:hover { transform:translateY(-6px);box-shadow:0 16px 36px rgba(0,0,0,.08); }
        .icon-box {
            width:52px;height:52px;border-radius:13px;
            display:flex;align-items:center;justify-content:center;font-size:1.3rem;
        }

        /* ── PLAN CARDS ──────────────────────────── */
        .plan-card { border-radius:16px;border:2px solid #e2e8f0;transition:all .3s; }
        .plan-card.popular {
            border-color:#1e40af;transform:scale(1.03);
            box-shadow:0 12px 36px rgba(30,64,175,.15);
        }
        .plan-card:hover:not(.popular) { border-color:#1e40af; }
        @media(max-width:767px){ .plan-card.popular { transform:none; } }

        /* ── STEPS ───────────────────────────────── */
        .step-circle {
            width:52px;height:52px;border-radius:50%;
            background:linear-gradient(135deg,#1e40af,#0ea5e9);
            color:#fff;font-weight:800;font-size:1.1rem;
            display:flex;align-items:center;justify-content:center;flex-shrink:0;
        }

        /* ── WHATSAPP FLOAT ──────────────────────── */
        .whatsapp-float {
            position:fixed;bottom:20px;right:20px;z-index:999;
            background:#25d366;color:#fff;width:54px;height:54px;
            border-radius:50%;display:flex;align-items:center;justify-content:center;
            font-size:1.5rem;box-shadow:0 4px 16px rgba(37,211,102,.45);
            text-decoration:none;transition:transform .2s;
        }
        .whatsapp-float:hover { transform:scale(1.1);color:#fff; }

        footer a { text-decoration:none;transition:color .2s; }
        footer a:hover { color:#fff !important; }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <div class="brand-mark">SWF</div>
            <span class="brand-name">SWF PORTAL</span>
        </a>
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1 mt-3 mt-lg-0">
                <li class="nav-item"><a href="#features" class="nav-link px-3">Features</a></li>
                <li class="nav-item"><a href="#pricing"  class="nav-link px-3">Pricing</a></li>
                <li class="nav-item"><a href="#how"      class="nav-link px-3">How It Works</a></li>
                <li class="nav-item mt-2 mt-lg-0">
                    <a href="{{ route('student.login') }}"
                       class="btn btn-outline-primary btn-sm px-3 w-100 w-lg-auto">
                        Student Login
                    </a>
                </li>
                <li class="nav-item mt-2 mt-lg-0">
                    <a href="{{ route('instructor.login') }}"
                       class="btn btn-primary btn-sm px-3 w-100 w-lg-auto">
                        Instructor Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="hero-section text-white">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 text-center text-lg-start">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2">
                    🎓 Student Welfare Foundation
                </span>
                <h1 class="hero-title mb-3">Online Test Platform<br>Made for Pakistan</h1>
                <p class="hero-sub mb-4">
                    Create professional exams, conduct tests securely with anti-cheat,
                    and analyze results — all in one powerful platform.
                </p>
                <div class="d-flex gap-3 flex-wrap justify-content-center justify-content-lg-start mb-4">
                    <a href="{{ route('student.register') }}"
                       class="btn btn-warning btn-lg px-4 fw-semibold">
                        <i class="bi bi-mortarboard me-2"></i>Join as Student
                    </a>
                    {{-- FIXED: "Start Teaching" → "Join as Instructor" --}}
                    <a href="{{ route('instructor.register') }}"
                       class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-person-workspace me-2"></i>Join as Instructor
                    </a>
                </div>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap small opacity-75">
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i>Free to Start</span>
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i>Anti-Cheat</span>
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i>Instant Results</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach([
                        ['icon'=>'bi-people-fill',  'label'=>'Students',      'val'=>'10,000+'],
                        ['icon'=>'bi-journal-text', 'label'=>'Tests Created', 'val'=>'5,000+'],
                        ['icon'=>'bi-shield-check', 'label'=>'Anti-Cheat',   'val'=>'Active'],
                        ['icon'=>'bi-trophy-fill',  'label'=>'Results',      'val'=>'Instant'],
                    ] as $s)
                    <div class="col-6">
                        <div class="stat-mini text-white">
                            <i class="bi {{ $s['icon'] }} fs-3 text-warning d-block mb-1"></i>
                            <div class="val">{{ $s['val'] }}</div>
                            <div class="lbl">{{ $s['label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section id="features" class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-2">Platform Features</span>
            <h2 class="fw-bold">Everything You Need</h2>
            <p class="text-muted">Complete online examination system built for Pakistani educators</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon'=>'bi-shield-lock-fill','color'=>'bg-danger bg-opacity-10 text-danger',
                 'title'=>'Anti-Cheat System',
                 'desc'=>'Detects tab switching and copy attempts. Auto-submits after set violations.'],
                ['icon'=>'bi-clock-fill','color'=>'bg-warning bg-opacity-10 text-warning',
                 'title'=>'Smart Timer',
                 'desc'=>'Countdown timer with automatic submission when time expires.'],
                ['icon'=>'bi-bar-chart-fill','color'=>'bg-success bg-opacity-10 text-success',
                 'title'=>'Instant Analytics',
                 'desc'=>'Scores, ranks, percentages — everything auto-calculated on submission.'],
                ['icon'=>'bi-file-earmark-arrow-down-fill','color'=>'bg-info bg-opacity-10 text-info',
                 'title'=>'Export Results',
                 'desc'=>'Download results as PDF, Excel, or CSV with one click.'],
                ['icon'=>'bi-shuffle','color'=>'bg-primary bg-opacity-10 text-primary',
                 'title'=>'Random Questions',
                 'desc'=>'Shuffle questions and options within each section to prevent cheating.'],
                ['icon'=>'bi-collection-fill','color'=>'bg-secondary bg-opacity-10 text-secondary',
                 'title'=>'Question Bank',
                 'desc'=>'Save MCQs for reuse across multiple tests and exams.'],
            ] as $f)
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 shadow-sm bg-white">
                    <div class="icon-box {{ $f['color'] }} mb-3"><i class="bi {{ $f['icon'] }}"></i></div>
                    <h5 class="fw-bold mb-2">{{ $f['title'] }}</h5>
                    <p class="text-muted mb-0 small">{{ $f['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section id="how" class="py-5">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mb-2">Simple Process</span>
            <h2 class="fw-bold">How It Works</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach([
                ['step'=>'1','title'=>'Register',
                 'desc'=>'Create a free account as instructor or student in under 2 minutes.',
                 'icon'=>'bi-person-plus'],
                ['step'=>'2','title'=>'Create or Join Test',
                 'desc'=>'Instructors build tests; students enter a test code to join.',
                 'icon'=>'bi-journal-plus'],
                ['step'=>'3','title'=>'Take the Exam',
                 'desc'=>'Real-time timer, anti-cheat monitoring, and auto-save answers.',
                 'icon'=>'bi-pencil-square'],
                ['step'=>'4','title'=>'View Results',
                 'desc'=>'Instant scores, rankings, and detailed analysis after submission.',
                 'icon'=>'bi-trophy'],
            ] as $step)
            <div class="col-sm-6 col-lg-3">
                <div class="text-center p-3">
                    <div class="step-circle mx-auto mb-3">{{ $step['step'] }}</div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 mb-3">
                        <i class="bi {{ $step['icon'] }} fs-2 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $step['title'] }}</h5>
                    <p class="text-muted small mb-0">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING — UPDATED to match subscription page exactly --}}
<section id="pricing" class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-2">Subscription Plans</span>
            <h2 class="fw-bold">Simple, Transparent Pricing</h2>
            <p class="text-muted">Choose a plan that fits your needs</p>
        </div>
        <div class="row g-4 justify-content-center align-items-stretch">

            {{-- BASIC --}}
            <div class="col-md-4">
                <div class="plan-card h-100 bg-white shadow-sm p-4">
                    <div class="text-center mb-3">
                        <div class="icon-box bg-secondary bg-opacity-10 text-secondary mx-auto mb-3">
                            <i class="bi bi-box"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Basic</h4>
                        <div class="mt-2">
                            <span class="fs-2 fw-bold">PKR 2,000</span>
                            <span class="text-muted small">/month</span>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-4">
                        @foreach(['10 Tests / month','100 Students','MCQ Builder','Basic Timer & Auto-Submit','PDF Result Download'] as $f)
                        <li class="d-flex align-items-center gap-2 py-1 small">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>{{ $f }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('pricing') }}" class="btn btn-outline-secondary w-100 fw-semibold">
                        Get Basic
                    </a>
                </div>
            </div>

            {{-- PRO — UPDATED: 20 tests, 250 students, PDF only --}}
            <div class="col-md-4">
                <div class="plan-card popular h-100 bg-white shadow-sm p-4">
                    <div class="text-center mb-2">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">⭐ Most Popular</span>
                    </div>
                    <div class="text-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                            <i class="bi bi-rocket-takeoff"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-primary">Pro</h4>
                        <div class="mt-2">
                            <span class="fs-2 fw-bold">PKR 3,500</span>
                            <span class="text-muted small">/month</span>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-4">
                        @foreach([
                            '20 Tests / month',
                            '250 Students',
                            'Advanced MCQ Builder',
                            'Anti-Cheat System',
                            'Question Bank',
                            'PDF Export',
                            'Negative Marking',
                            'Random Q & Option Order',
                        ] as $f)
                        <li class="d-flex align-items-center gap-2 py-1 small">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>{{ $f }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('pricing') }}" class="btn btn-primary w-100 fw-semibold">
                        Get Pro
                    </a>
                </div>
            </div>

            {{-- MAX — UPDATED: Anti-Cheating instead of API Access --}}
            <div class="col-md-4">
                <div class="plan-card h-100 bg-white shadow-sm p-4"
                     style="border-color:#7c3aed">
                    <div class="text-center mb-3">
                        <div class="icon-box mx-auto mb-3"
                             style="background:rgba(124,58,237,.1);color:#7c3aed">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h4 class="fw-bold mb-0" style="color:#7c3aed">Max</h4>
                        <div class="mt-2">
                            <span class="fs-2 fw-bold">PKR 7,000</span>
                            <span class="text-muted small">/month</span>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-4">
                        @foreach([
                            'Unlimited Tests',
                            'Unlimited Students',
                            'All Pro Features',
                            'Full Analytics Dashboard',
                            'Advanced Anti-Cheating System',
                            'Priority Support',
                            'Custom Branding',
                            'PDF + Excel + CSV Export',
                            'Dedicated Account Manager',
                        ] as $f)
                        <li class="d-flex align-items-center gap-2 py-1 small">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>{{ $f }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('pricing') }}"
                       class="btn w-100 fw-semibold text-white"
                       style="background:linear-gradient(135deg,#7c3aed,#5b21b6)">
                        Get Max
                    </a>
                </div>
            </div>

        </div>
        <p class="text-center text-muted small mt-4">
            <i class="bi bi-info-circle me-1"></i>
            Yearly plans available with up to 52% discount.
            Payment via JazzCash, EasyPaisa, or Binance Pay.
        </p>
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,#1e40af,#059669)" class="py-5">
    <div class="container text-center text-white py-3">
        <h2 class="fw-bold mb-3">Start Testing Today — It's Free!</h2>
        <p class="lead opacity-75 mb-4">Join thousands of educators and students on SWF PORTAL</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('student.register') }}" class="btn btn-warning btn-lg px-4 fw-semibold">
                <i class="bi bi-mortarboard me-2"></i>Register as Student
            </a>
            <a href="{{ route('instructor.register') }}" class="btn btn-outline-light btn-lg px-4">
                <i class="bi bi-person-workspace me-2"></i>Join as Instructor
            </a>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-4 text-center text-md-start">
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start mb-1">
                    <div style="width:32px;height:32px;border-radius:7px;
                                background:linear-gradient(135deg,#1e40af,#0ea5e9);
                                display:flex;align-items:center;justify-content:center;
                                font-size:.7rem;font-weight:900;color:#fff">SWF</div>
                    <span class="fw-bold">SWF PORTAL</span>
                </div>
                <p class="text-white-50 small mb-0">Student Welfare Foundation</p>
            </div>
            <div class="col-md-4 text-center">
                <p class="text-white-50 small mb-0">
                    &copy; {{ date('Y') }} Student Welfare Foundation.<br>All rights reserved.
                </p>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <a href="https://wa.me/923148379859" target="_blank"
                   class="text-white-50 text-decoration-none d-block d-md-inline me-md-3 mb-1 mb-md-0 small">
                    <i class="bi bi-whatsapp text-success me-1"></i>+92 314 8379859
                </a>
                <a href="mailto:swfhelpers@gmail.com" class="text-white-50 text-decoration-none small">
                    <i class="bi bi-envelope me-1"></i>swfhelpers@gmail.com
                </a>
            </div>
        </div>
    </div>
</footer>

<a href="https://wa.me/923148379859" class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>