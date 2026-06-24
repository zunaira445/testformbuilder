{{-- FILE PATH: resources/views/profile/show.blade.php --}}
@extends('layouts.app')
@section('title', 'My Profile')
@section('content')

@php $user = auth()->user(); @endphp

<div class="row g-4 justify-content-center">
<div class="col-lg-8">

    {{-- ── Profile Header Card ────────────────────────────── --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#1e40af,#1d4ed8);border-radius:16px;overflow:hidden">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4 flex-wrap">

                {{-- Avatar --}}
                <div class="position-relative flex-shrink-0">
                    <img src="{{ $user->avatarUrl() }}"
                         alt="{{ $user->name }}"
                         class="rounded-circle border border-white border-3"
                         style="width:80px;height:80px;object-fit:cover;box-shadow:0 4px 16px rgba(0,0,0,0.2)">
                    {{-- Change avatar trigger --}}
                    <button onclick="document.getElementById('avatarInput').click()"
                            class="btn btn-sm position-absolute d-flex align-items-center justify-content-center"
                            style="bottom:0;right:0;width:26px;height:26px;border-radius:50%;background:#fff;color:#1e40af;padding:0;box-shadow:0 2px 6px rgba(0,0,0,0.2);"
                            title="Change photo">
                        <i class="bi bi-camera-fill" style="font-size:11px"></i>
                    </button>
                </div>

                {{-- Info --}}
                <div class="text-white flex-grow-1">
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <div style="opacity:.8;font-size:14px">{{ $user->email }}</div>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <span class="badge bg-white text-primary fw-semibold">{{ ucfirst($user->role) }}</span>
                        @if($user->institution)
                            <span class="badge" style="background:rgba(255,255,255,0.2)">{{ $user->institution }}</span>
                        @endif
                        @if($user->city)
                            <span class="badge" style="background:rgba(255,255,255,0.2)">
                                <i class="bi bi-geo-alt me-1"></i>{{ $user->city }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Flash Messages ──────────────────────────────────── --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible d-flex gap-2 align-items-start mb-4" style="border-radius:12px;border:none;background:#f0fdf4">
        <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible d-flex gap-2 align-items-start mb-4" style="border-radius:12px;border:none">
        <i class="bi bi-exclamation-circle-fill mt-1 flex-shrink-0"></i>
        <div><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ── Edit Profile Form ───────────────────────────────── --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px">
        <div class="card-header bg-transparent py-3 border-bottom">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-person-gear me-2 text-primary"></i>Edit Profile
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                @csrf @method('PUT')

                {{-- Hidden avatar input --}}
                <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp"
                       class="d-none" onchange="previewAvatar(this)">

                {{-- Avatar Preview Section --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:#f8fafc;border-radius:12px;border:1.5px dashed #cbd5e1">
                    <img id="avatarPreview"
                         src="{{ $user->avatarUrl() }}"
                         class="rounded-circle"
                         style="width:60px;height:60px;object-fit:cover;border:2px solid #e2e8f0">
                    <div>
                        <p class="fw-semibold mb-1" style="font-size:14px">Profile Picture</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button"
                                    onclick="document.getElementById('avatarInput').click()"
                                    class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:13px">
                                <i class="bi bi-upload me-1"></i>Upload Photo
                            </button>
                            @if($user->avatar)
                            <form method="POST" action="{{ route('profile.avatar.remove') }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:13px">
                                    <i class="bi bi-trash me-1"></i>Remove
                                </button>
                            </form>
                            @endif
                        </div>
                        <p class="text-muted mb-0 mt-1" style="font-size:12px">JPG, PNG or WebP — max 2MB</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">Email Address</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                        <div class="form-text">Email cannot be changed.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">Phone Number</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $user->phone) }}" placeholder="03XX-XXXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">Institution / School</label>
                        <input type="text" name="institution" class="form-control"
                               value="{{ old('institution', $user->institution) }}"
                               placeholder="e.g. Punjab University">
                    </div>

                    @if($user->isStudent())
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $user->city) }}" placeholder="e.g. Lahore">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">Roll Number</label>
                        <input type="text" name="roll_number" class="form-control"
                               value="{{ old('roll_number', $user->roll_number) }}"
                               placeholder="e.g. 2023-CS-001">
                    </div>
                    @endif

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="dark_mode" id="darkModeToggle"
                                   value="1" {{ $user->dark_mode ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="darkModeToggle" style="font-size:14px">
                                <i class="bi bi-moon me-1"></i>Dark Mode
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius:10px">
                        <i class="bi bi-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Change Password ─────────────────────────────────── --}}
    <div class="card border-0 shadow-sm" style="border-radius:16px">
        <div class="card-header bg-transparent py-3 border-bottom">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-shield-lock me-2 text-warning"></i>Change Password
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:14px">
                            Current Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">
                            New Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password" class="form-control" required
                               placeholder="Min 8 chars, upper, lower, number, symbol">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Minimum 8 characters with uppercase, lowercase, number, and symbol.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:14px">
                            Confirm New Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-4 fw-semibold" style="border-radius:10px">
                        <i class="bi bi-key me-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</div>

<script>
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 2 * 1024 * 1024) {
        alert('Image is too large. Maximum size is 2MB.');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('avatarPreview').src = e.target.result;
        // Also update header avatar
        document.querySelector('.card-body img.rounded-circle')?.setAttribute('src', e.target.result);
    };
    reader.readAsDataURL(file);
}
</script>

@endsection