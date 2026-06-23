{{-- ============================================================ --}}
{{-- FILE PATH: resources/views/layouts/partials/sidebar-instructor.blade.php --}}
{{-- ============================================================ --}}

<ul class="nav flex-column gap-1">
    <li><a href="{{ route('instructor.dashboard') }}" class="nav-link {{ request()->routeIs('instructor.dashboard') ? 'active':'' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a></li>
    <li><a href="{{ route('instructor.tests.index') }}" class="nav-link {{ request()->routeIs('instructor.tests*') ? 'active':'' }}">
        <i class="bi bi-journal-plus me-2"></i> My Tests
    </a></li>
    <li><a href="{{ route('instructor.question-bank') }}" class="nav-link {{ request()->routeIs('instructor.question-bank*') ? 'active':'' }}">
        <i class="bi bi-collection me-2"></i> Question Bank
    </a></li>
    <li><a href="{{ route('instructor.analytics') }}" class="nav-link {{ request()->routeIs('instructor.analytics*') ? 'active':'' }}">
        <i class="bi bi-bar-chart-line me-2"></i> Analytics
    </a></li>
    <li><a href="{{ route('pricing') }}" class="nav-link">
        <i class="bi bi-gem me-2"></i> Upgrade Plan
    </a></li>
    <li><a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile*') ? 'active':'' }}">
        <i class="bi bi-person-circle me-2"></i> Profile
    </a></li>
</ul>