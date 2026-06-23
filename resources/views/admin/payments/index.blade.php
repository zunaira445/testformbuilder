{{-- FILE PATH: resources/views/admin/payments/index.blade.php --}}
@extends('layouts.app')
@section('title','Manage Payments')
@section('content')

<h4 class="fw-bold mb-4"><i class="bi bi-credit-card me-2 text-success"></i>Payment Requests</h4>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Screenshot</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($payments as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>
                    <div class="fw-semibold">{{ $p->user->name }}</div>
                    <small class="text-muted">{{ $p->user->email }}</small>
                </td>
                <td>
                    <span class="badge bg-primary">{{ $p->plan->name }}</span>
                    <div class="small text-muted">{{ $p->plan->duration_days }} days</div>
                </td>
                <td><span class="badge bg-secondary text-uppercase">{{ $p->method }}</span></td>
                <td class="fw-semibold">PKR {{ number_format($p->amount) }}</td>
                <td>
                    @if($p->screenshot)
                    <a href="{{ asset('storage/'.$p->screenshot) }}" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-image me-1"></i>View
                    </a>
                    @else
                    <span class="text-muted small">No screenshot</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $p->status==='approved'?'success':($p->status==='rejected'?'danger':'warning') }}">
                        {{ ucfirst($p->status) }}
                    </span>
                    @if($p->admin_note)
                    <div class="small text-muted">{{ Str::limit($p->admin_note,30) }}</div>
                    @endif
                </td>
                <td><small>{{ $p->created_at->format('d M Y') }}</small></td>
                <td class="text-center">
                    @if($p->status === 'pending')
                    <div class="d-flex gap-1 justify-content-center">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $p->id }}">
                            <i class="bi bi-check-lg"></i> Approve
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}">
                            <i class="bi bi-x-lg"></i> Reject
                        </button>
                    </div>

                    {{-- Approve Modal --}}
                    <div class="modal fade" id="approveModal{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white"><h5 class="modal-title">Approve Payment</h5></div>
                                <form method="POST" action="{{ route('admin.payments.approve',$p) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Approve payment of <strong>PKR {{ number_format($p->amount) }}</strong> by <strong>{{ $p->user->name }}</strong> for <strong>{{ $p->plan->name }}</strong> plan?</p>
                                        <textarea name="admin_note" class="form-control" placeholder="Optional note..." rows="2"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Approve & Activate</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Reject Modal --}}
                    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white"><h5 class="modal-title">Reject Payment</h5></div>
                                <form method="POST" action="{{ route('admin.payments.reject',$p) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <textarea name="admin_note" class="form-control" placeholder="Reason for rejection..." rows="3"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <span class="text-muted small">{{ $p->approved_at?->format('d M Y') }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-5 text-muted">No payment requests yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent">{{ $payments->links() }}</div>
</div>
@endsection