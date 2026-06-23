{{-- FILE PATH: resources/views/layouts/partials/sidebar-student.blade.php --}}

<ul class="nav flex-column gap-1">
    <li><a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active':'' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a></li>
    <li><a href="{{ route('student.my-tests') }}" class="nav-link {{ request()->routeIs('student.my-tests*') ? 'active':'' }}">
        <i class="bi bi-journal-check me-2"></i> My Tests
    </a></li>
    <li>
        <a href="#joinTestCollapse" class="nav-link" data-bs-toggle="collapse">
            <i class="bi bi-key me-2"></i> Join Test
        </a>
        <div class="collapse px-2 py-2" id="joinTestCollapse">
            <form action="#" onsubmit="window.location='/test/join/'+this.code.value; return false;">
                <div class="input-group input-group-sm">
                    <input name="code" class="form-control" placeholder="Enter Test Code" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;">
                    <button class="btn btn-sm btn-warning" type="submit"><i class="bi bi-arrow-right"></i></button>
                </div>
            </form>
        </div>
    </li>
    <li><a href="{{ route('pricing') }}" class="nav-link">
        <i class="bi bi-gem me-2"></i> Subscription
    </a></li>
    <li><a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile*') ? 'active':'' }}">
        <i class="bi bi-person-circle me-2"></i> Profile
    </a></li>
</ul>