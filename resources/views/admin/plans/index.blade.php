{{-- FILE PATH: resources/views/admin/plans/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Subscription Plans')
@section('content')

@php $plans = \App\Models\SubscriptionPlan::withCount('subscriptions')->latest()->get(); @endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-layers me-2 text-primary"></i>Subscription Plans</h4>
    <button class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#addPlanModal">
        <i class="bi bi-plus-circle me-2"></i>Add Plan
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4 mb-4">
@foreach($plans as $plan)
<div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 {{ !$plan->is_active ? 'opacity-75' : '' }}">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ $plan->name }}</h5>
                    <div class="fs-3 fw-bold text-primary">PKR {{ number_format($plan->price) }}<span class="fs-6 text-muted fw-normal">/mo</span></div>
                </div>
                <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">
                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="text-muted small mb-3">
                <i class="bi bi-people me-1"></i>{{ $plan->subscriptions_count }} active subscriptions
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary flex-grow-1"
                        data-bs-toggle="modal"
                        data-bs-target="#editPlanModal{{ $plan->id }}">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
                <form method="POST" action="{{ route('admin.plans.toggle', $plan) }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-{{ $plan->is_active ? 'warning' : 'success' }}">
                        <i class="bi bi-toggle-{{ $plan->is_active ? 'on' : 'off' }}"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editPlanModal{{ $plan->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Plan: {{ $plan->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Plan Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Monthly Price (PKR)</label>
                            <input type="number" name="price" class="form-control" value="{{ $plan->price }}" required min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>

{{-- Subscriptions Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-success"></i>Active Subscriptions</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>User</th><th>Plan</th><th>Expires</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse(\App\Models\UserSubscription::with(['user','plan'])->where('is_active',true)->latest()->take(20)->get() as $sub)
            <tr>
                <td>
                    <div class="fw-semibold">{{ $sub->user->name }}</div>
                    <small class="text-muted">{{ $sub->user->email }}</small>
                </td>
                <td><span class="badge bg-primary">{{ $sub->plan->name }}</span></td>
                <td>
                    <span class="{{ $sub->expires_at->isPast() ? 'text-danger' : 'text-success' }} fw-semibold">
                        {{ $sub->expires_at->format('d M Y') }}
                    </span>
                    <div class="small text-muted">{{ $sub->expires_at->diffForHumans() }}</div>
                </td>
                <td>
                    <span class="badge bg-{{ $sub->is_active && !$sub->expires_at->isPast() ? 'success' : 'danger' }}">
                        {{ $sub->is_active && !$sub->expires_at->isPast() ? 'Active' : 'Expired' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4 text-muted">No active subscriptions.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add Plan Modal --}}
<div class="modal fade" id="addPlanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Plan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.plans.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plan Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Basic, Pro, Max" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Monthly Price (PKR) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" placeholder="e.g. 2000" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold">Create Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection