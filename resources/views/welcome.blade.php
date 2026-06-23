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
        .hero-section {
            background: linear-gradient(135deg, #1e40af 0%, #059669 100%);
            min-height: 100vh;
            display: flex; align-items: center;
        }
        .feature-card {
            border: none; border-radius: 16px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: default;
        }
        .feature-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .icon-box {
            width: 60px; height: 60px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
        .navbar-brand span { font-weight: 800; color: #1e40af; }
        .plan-card { border-radius: 16px; border: 2px solid #e5e7eb; transition: all 0.3s; }
        .plan-card.popular { border-color: #1e40af; transform: scale(1.05); }
        .plan-card:hover { border-color: #1e40af; }
        .whatsapp-float {
            position: fixed; bottom: 25px; right: 25px; z-index: 999;
            background: #25d366; color: white; width: 58px; height: 58px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; box-shadow: 0 4px 20px rgba(37,211,102,0.5);
            text-decoration: none; transition: transform 0.2s;
        }
        .whatsapp-float:hover { transform: scale(1.1); color: white; }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <div class="bg-primary rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="width:38px;height:38px;font-size:13px">SWF</div>
            <span>SWF PORTAL</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
                <li class="nav-item"><a href="{{ route('pricing') }}" class="nav-link">Pricing</a></li>
                <li class="nav-item"><a href="{{ route('student.login') }}" class="btn btn-outline-primary btn-sm px-3">Student Login</a></li>
                <li class="nav-item"><a href="{{ route('instructor.login') }}" class="btn btn-primary btn-sm px-3">Instructor Login</a></li>
            </ul>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="hero-section text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2">🎓 Student Welfare Foundation</span>
                <h1 class="display-4 fw-bold mb-4">Online Test Platform<br>Made for Pakistan</h1>
                <p class="lead opacity-75 mb-4">
                    Create professional tests, conduct exams securely, and analyze results — all in one powerful platform.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('student.register') }}" class="btn btn-warning btn-lg px-4 fw-semibold">
                        <i class="bi bi-mortarboard me-2"></i>Join as Student
                    </a>
                    <a href="{{ route('instructor.register') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-person-workspace me-2"></i>Start Teaching
                    </a>
                </div>
                <div class="d-flex gap-4 mt-4 text-white-50 small">
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> Free to Start</span>
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> Anti-Cheat</span>
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> Instant Results</span>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-5 mt-lg-0">
                <div class="bg-white bg-opacity-10 rounded-3 p-4 border border-white border-opacity-25">
                    <div class="row g-3">
                        @foreach([
                            ['icon'=>'bi-people-fill','color'=>'bg-warning','label'=>'Students','val'=>'10,000+'],
                            ['icon'=>'bi-journal-text','color'=>'bg-success','label'=>'Tests Created','val'=>'5,000+'],
                            ['icon'=>'bi-shield-check','color'=>'bg-info','label'=>'Anti-Cheat','val'=>'Active'],
                            ['icon'=>'bi-trophy-fill','color'=>'bg-danger','label'=>'Results','val'=>'Instant'],
                        ] as $s)
                        <div class="col-6">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3 text-center">
                                <i class="bi {{ $s['icon'] }} fs-2 text-warning"></i>
                                <div class="fw-bold fs-4 mt-1">{{ $s['val'] }}</div>
                                <div class="small opacity-75">{{ $s['label'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section id="features" class="py-6 bg-light" style="padding:80px 0">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Everything You Need</h2>
            <p class="text-muted">Complete online examination system</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon'=>'bi-shield-lock-fill','color'=>'bg-danger bg-opacity-10 text-danger','title'=>'Anti-Cheat System','desc'=>'Auto-detect tab switching, copy attempts & violations. Auto-submit on 3rd violation.'],
                ['icon'=>'bi-clock-fill','color'=>'bg-warning bg-opacity-10 text-warning','title'=>'Timer & Auto Submit','desc'=>'Countdown timer with auto-submission when time expires. Never miss a deadline.'],
                ['icon'=>'bi-bar-chart-fill','color'=>'bg-success bg-opacity-10 text-success','title'=>'Instant Analytics','desc'=>'Rank, percentage, time taken — everything auto-calculated after submission.'],
                ['icon'=>'bi-file-earmark-arrow-down-fill','color'=>'bg-info bg-opacity-10 text-info','title'=>'Export Results','desc'=>'Download results in PDF, Excel or CSV formats instantly.'],
                ['icon'=>'bi-shuffle','color'=>'bg-primary bg-opacity-10 text-primary','title'=>'Random Questions','desc'=>'Shuffle questions and options to prevent cheating between students.'],
                ['icon'=>'bi-collection-fill','color'=>'bg-secondary bg-opacity-10 text-secondary','title'=>'Question Bank','desc'=>'Save questions and reuse them across multiple tests easily.'],
            ] as $f)
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 shadow-sm">
                    <div class="icon-box {{ $f['color'] }} mb-3">
                        <i class="bi {{ $f['icon'] }}"></i>
                    </div>
                    <h5 class="fw-bold">{{ $f['title'] }}</h5>
                    <p class="text-muted mb-0">{{ $f['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-5" style="background:linear-gradient(135deg,#1e40af,#059669)">
    <div class="container text-center text-white py-4">
        <h2 class="fw-bold mb-3">Start Testing Today — It's Free!</h2>
        <p class="lead opacity-75 mb-4">Join thousands of educators and students on SWF PORTAL</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('student.register') }}" class="btn btn-warning btn-lg px-5 fw-semibold">Register as Student</a>
            <a href="{{ route('instructor.register') }}" class="btn btn-outline-light btn-lg px-5">Register as Instructor</a>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">Copyright &copy; {{ date('Y') }} Student Welfare Foundation</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <a href="https://wa.me/923148379859" class="text-white-50 text-decoration-none me-3">
                    <i class="bi bi-whatsapp text-success"></i> +92 314 8379859
                </a>
                <a href="mailto:swfhelpers@gmail.com" class="text-white-50 text-decoration-none">
                    <i class="bi bi-envelope"></i> swfhelpers@gmail.com
                </a>
            </div>
        </div>
    </div>
</footer>

{{-- WhatsApp Float Button --}}
<a href="https://wa.me/923148379859" class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>