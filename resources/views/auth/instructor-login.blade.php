{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/auth/instructor-login.blade.php --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Instructor Login — SWF PORTAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#059669,#065f46);min-height:100vh;display:flex;align-items:center;}
        .auth-card{border:none;border-radius:20px;box-shadow:0 25px 50px rgba(0,0,0,0.2);}
        .form-control{border-radius:10px;padding:12px 15px;border:2px solid #e5e7eb;}
        .form-control:focus{border-color:#059669;box-shadow:0 0 0 3px rgba(5,150,105,0.1);}
        .btn-success{border-radius:10px;padding:12px;font-weight:600;background:linear-gradient(135deg,#059669,#047857);border:none;}
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card auth-card p-4">
                <div class="text-center mb-4">
                    <div class="mx-auto mb-3 bg-success rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="width:60px;height:60px;font-size:14px">SWF</div>
                    <h4 class="fw-bold">Instructor Login</h4>
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
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="pwd" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="let p=document.getElementById('pwd');p.type=p.type==='password'?'text':'password'"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login as Instructor
                    </button>
                </form>
                <hr class="my-3">
                <div class="text-center small">
                    <p class="mb-1 text-muted">No account? <a href="{{ route('instructor.register') }}" class="fw-semibold text-success">Register as Instructor</a></p>
                    <p class="mb-0 text-muted">Are you a student? <a href="{{ route('student.login') }}" class="fw-semibold text-primary">Student Login</a></p>
                </div>
                <div class="text-center mt-3"><a href="{{ route('home') }}" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Back to Home</a></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/auth/instructor-register.blade.php --}}
{{-- ============================================================ --}}
{{-- NOTE: Create this file separately and paste the code below --}}