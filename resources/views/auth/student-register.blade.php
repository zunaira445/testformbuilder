{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/auth/student-register.blade.php --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student Register — SWF PORTAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#1e40af,#059669);min-height:100vh;padding:30px 0;}
        .auth-card{border:none;border-radius:20px;box-shadow:0 25px 50px rgba(0,0,0,0.2);}
        .form-control,.form-select{border-radius:10px;padding:11px 15px;border:2px solid #e5e7eb;}
        .form-control:focus,.form-select:focus{border-color:#1e40af;box-shadow:0 0 0 3px rgba(30,64,175,0.1);}
        .btn-success{border-radius:10px;padding:12px;font-weight:600;}
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card auth-card p-4">
                <div class="text-center mb-4">
                    <div class="mx-auto mb-3 bg-success rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="width:60px;height:60px;font-size:16px">SWF</div>
                    <h4 class="fw-bold">Create Student Account</h4>
                    <p class="text-muted small">Join SWF PORTAL — Student Welfare Foundation</p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif

                <form method="POST" action="{{ route('student.register.post') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="03xxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Institution / School</label>
                            <input type="text" name="institution" class="form-control" value="{{ old('institution') }}" placeholder="Your school/college name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="Your city">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-person-check me-2"></i>Create Account
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-3">
                <div class="text-center small">
                    <p class="mb-1 text-muted">Already have an account? <a href="{{ route('student.login') }}" class="fw-semibold text-primary">Login</a></p>
                    <p class="mb-0 text-muted">Are you an instructor? <a href="{{ route('instructor.register') }}" class="fw-semibold text-success">Register as Instructor</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>