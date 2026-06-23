{{-- FILE PATH: resources/views/profile/show.blade.php --}}
@extends('layouts.app')
@section('title', 'My Profile')
@section('content')

@php $user = auth()->user(); @endphp

<div class="row g-4 justify-content-center">
    <div class="col-lg-8">

        {{-- Profile Header --}}
        <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#1e40af,#1d4ed8)">
            <div class="card-body p-4 text-white">
                <div class="d-flex align-items-center gap-4">
                    <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center fw-bold"
                         style="width:72px;height:72px;font-size:1.8rem;color:#fff;flex-shrink:0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                        <div class="opacity-75 small">{{ $user->email }}</div>
                        <div class="mt-2 d-flex gap-2 flex-wrap">
                            <span class="badge bg-white text-primary fw-semibold">{{ ucfirst($user->role) }}</span>
                            @if($user->institution)
                            <span class="badge bg-white bg-opacity-20">{{ $user->institution }}</span>
                            @endif
                            @if($user->city)
                            <span class="badge bg-white bg-opacity-20"><i class="bi bi-geo-alt me-1"></i>{{ $user->city }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Edit Profile Form --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-gear me-2 text-primary"></i>Edit Profile</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                            <div class="form-text">Email cannot be changed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="03XX-XXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Institution / School</label>
                            <input type="text" name="institution" class="form-control" value="{{ old('institution', $user->institution) }}" placeholder="e.g. Punjab University">
                        </div>
                        @if($user->isStudent())
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" placeholder="e.g. Lahore">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Roll Number</label>
                            <input type="text" name="roll_number" class="form-control" value="{{ old('roll_number', $user->roll_number) }}" placeholder="e.g. 2023-CS-001">
                        </div>
                        @endif
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="dark_mode" id="darkMode" value="1" {{ $user->dark_mode ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="darkMode">
                                    <i class="bi bi-moon me-1"></i>Dark Mode
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-warning"></i>Change Password</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required
                                   placeholder="Min 8 chars, upper, lower, number, symbol">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Minimum 8 characters with uppercase, lowercase, number, and special character.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning px-4 fw-semibold">
                            <i class="bi bi-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection