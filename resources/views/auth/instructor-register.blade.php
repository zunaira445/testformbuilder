{{-- FILE PATH: resources/views/auth/instructor-register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Instructor Register — SWF PORTAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#059669,#065f46);min-height:100vh;padding:30px 0;}
        .auth-card{border:none;border-radius:20px;box-shadow:0 25px 50px rgba(0,0,0,0.2);}
        .form-control{border-radius:10px;padding:11px 15px;border:2px solid #e5e7eb;}
        .form-control:focus{border-color:#059669;box-shadow:0 0 0 3px rgba(5,150,105,0.1);}
        .btn-success{border-radius:10px;padding:12px;font-weight:600;}
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card auth-card p-4">
                <div class="text-center mb-4">
                    <div class="mx-auto mb-3 bg-success rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="width:60px;height:60px;font-size:14px">SWF</div>
                    <h4 class="fw-bold">Instructor Registration</h4>
                    <p class="text-muted small">SWF PORTAL — Student Welfare Foundation</p>
                </div>
                @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif
                <form method="POST" action="{{ route('instructor.register.post') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="03xxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Institution / Organization</label>
                            <input type="text" name="institution" class="form-control" value="{{ old('institution') }}" placeholder="School, Academy, University...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100 mt-2">
                                <i class="bi bi-person-workspace me-2"></i>Register as Instructor
                            </button>
                        </div>
                    </div>
                </form>
                <hr class="my-3">
                <div class="text-center small">
                    <p class="mb-0 text-muted">Already registered? <a href="{{ route('instructor.login') }}" class="fw-semibold text-success">Login Here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>