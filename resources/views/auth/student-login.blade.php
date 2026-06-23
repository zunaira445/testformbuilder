{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/auth/student-login.blade.php --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student Login — SWF PORTAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#1e40af 0%,#059669 100%);min-height:100vh;display:flex;align-items:center;}
        .auth-card{border:none;border-radius:20px;box-shadow:0 25px 50px rgba(0,0,0,0.2);}
        .auth-logo{width:60px;height:60px;background:linear-gradient(135deg,#1e40af,#059669);border-radius:15px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:16px;}
        .form-control{border-radius:10px;padding:12px 15px;border:2px solid #e5e7eb;}
        .form-control:focus{border-color:#1e40af;box-shadow:0 0 0 3px rgba(30,64,175,0.1);}
        .btn-primary{border-radius:10px;padding:12px;font-weight:600;background:linear-gradient(135deg,#1e40af,#1d4ed8);border:none;}
        .btn-primary:hover{background:linear-gradient(135deg,#1d4ed8,#2563eb);}
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card auth-card p-4">
                <div class="text-center mb-4">
                    <div class="auth-logo mx-auto mb-3">SWF</div>
                    <h4 class="fw-bold">Student Login</h4>
                    <p class="text-muted small">SWF PORTAL — Student Welfare Foundation</p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="pwd" class="form-control" placeholder="Enter password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()"><i class="bi bi-eye" id="eyeIcon"></i></button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label small" for="remember">Remember me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>

                <hr class="my-3">
                <div class="text-center small">
                    <p class="mb-1 text-muted">Don't have an account? <a href="{{ route('student.register') }}" class="fw-semibold text-primary">Register Here</a></p>
                    <p class="mb-0 text-muted">Are you an instructor? <a href="{{ route('instructor.login') }}" class="fw-semibold text-success">Instructor Login</a></p>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('home') }}" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePwd(){
    const p=document.getElementById('pwd');
    const e=document.getElementById('eyeIcon');
    if(p.type==='password'){p.type='text';e.className='bi bi-eye-slash';}
    else{p.type='password';e.className='bi bi-eye';}
}
</script>
</body>
</html>