{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/layouts/partials/sidebar-admin.blade.php --}}
{{-- ============================================================ --}}

<ul class="nav flex-column gap-1">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Manage Users
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.tests') }}" class="nav-link {{ request()->routeIs('admin.tests*') ? 'active' : '' }}">
            <i class="bi bi-journal-text me-2"></i> All Tests
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="bi bi-credit-card me-2"></i> Payments
            @php $pending = \App\Models\Payment::where('status','pending')->count(); @endphp
            @if($pending > 0)
                <span class="badge bg-danger ms-1">{{ $pending }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.plans') }}" class="nav-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
            <i class="bi bi-layers me-2"></i> Subscription Plans
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="bi bi-gear me-2"></i> Site Settings
        </a>
    </li>
</ul>