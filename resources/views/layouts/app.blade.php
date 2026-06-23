{{-- FILE PATH: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — SWF Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --swf-blue:      #1e40af;
            --swf-blue-dark: #1e3a8a;
            --swf-teal:      #0ea5e9;
            --swf-sidebar-w: 248px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #0f172a; }

        /* ── DESKTOP SIDEBAR ─────────────────────────────── */
        .sidebar {
            width: var(--swf-sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--swf-blue-dark) 0%, var(--swf-blue) 100%);
            position: fixed; top: 0; left: 0;
            z-index: 200;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 20px rgba(30,58,138,.15);
            transition: transform .3s ease;
        }
        .sidebar .brand {
            padding: 1rem 1.1rem .9rem;
            border-bottom: 1px solid rgba(255,255,255,.12);
            display: flex; align-items: center; gap: .7rem;
            flex-shrink: 0;
        }
        .brand-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg,#fff,#e0e7ff);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; font-weight: 900; color: var(--swf-blue);
            flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .brand-title  { font-size: .88rem; font-weight: 800; color: #fff; line-height: 1.2; }
        .brand-sub    { font-size: .6rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .6px; }
        .sidebar nav  { flex: 1; padding: .4rem 0; overflow-y: auto; }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            border-radius: 8px;
            margin: 1px 8px; padding: 9px 13px;
            font-size: .84rem; font-weight: 500;
            transition: all .18s;
            display: flex; align-items: center; gap: .5rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.16); color: #fff;
        }
        .sidebar .nav-link.active { font-weight: 700; box-shadow: inset 3px 0 0 var(--swf-teal); }
        .sidebar-footer {
            padding: .65rem 1rem;
            border-top: 1px solid rgba(255,255,255,.1);
            font-size: .68rem; color: rgba(255,255,255,.35); text-align: center;
            flex-shrink: 0;
        }

        /* ── MAIN CONTENT ────────────────────────────────── */
        .main-content { margin-left: var(--swf-sidebar-w); min-height: 100vh; }

        /* ── TOPBAR ──────────────────────────────────────── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .6rem 1.25rem;
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            gap: .5rem;
        }
        .topbar .page-title { font-size: .88rem; font-weight: 700; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 55vw; }
        .topbar .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg,var(--swf-blue),var(--swf-teal));
            color: #fff; font-weight: 700; font-size: .78rem;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .topbar .hamburger { display: none; }

        /* ── CONTENT ─────────────────────────────────────── */
        .content-area { padding: 1.25rem; }

        /* ── MOBILE OFFCANVAS SIDEBAR ────────────────────── */
        #mobileNav .offcanvas-body { padding: 0; }
        #mobileNav .offcanvas-header {
            background: var(--swf-blue-dark);
            color: #fff; border: none;
            padding: .9rem 1rem;
        }
        #mobileNav .btn-close { filter: invert(1) grayscale(1) brightness(2); }
        #mobileNav .mobile-sidebar-inner {
            background: linear-gradient(180deg,var(--swf-blue-dark),var(--swf-blue));
            min-height: 100%;
            display: flex; flex-direction: column;
        }
        #mobileNav nav { flex: 1; padding: .5rem 0; }
        #mobileNav .nav-link {
            color: rgba(255,255,255,.8);
            border-radius: 8px; margin: 1px 8px; padding: 11px 14px;
            font-size: .9rem; font-weight: 500;
            display: flex; align-items: center; gap: .6rem;
        }
        #mobileNav .nav-link:hover,
        #mobileNav .nav-link.active { background: rgba(255,255,255,.16); color: #fff; font-weight: 700; }
        #mobileNav .mobile-footer {
            padding: .75rem 1rem;
            border-top: 1px solid rgba(255,255,255,.1);
            font-size: .7rem; color: rgba(255,255,255,.35); text-align: center;
        }

        /* ── RESPONSIVE BREAKPOINTS ──────────────────────── */
        @media (max-width: 991px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
            .topbar .hamburger { display: flex; }
            .content-area { padding: 1rem .85rem; }
        }
        @media (max-width: 575px) {
            .content-area { padding: .85rem .65rem; }
        }

        /* ── UTILITIES ───────────────────────────────────── */
        .card { border-radius: 12px; }
        .table-responsive { border-radius: 0 0 12px 12px; overflow: hidden; }

        @stack('extra-styles')
    </style>
    @stack('styles')
</head>
<body>

@auth
{{-- ── DESKTOP SIDEBAR ──────────────────────────────────── --}}
<aside class="sidebar">
    <div class="brand">
        <div class="brand-logo">SWF</div>
        <div>
            <div class="brand-title">SWF Portal</div>
            <div class="brand-sub">{{ ucfirst(auth()->user()->role) }} Panel</div>
        </div>
    </div>
    <nav>
        @if(auth()->user()->role === 'admin')
            @include('layouts.partials.sidebar-admin')
        @elseif(auth()->user()->role === 'instructor')
            @include('layouts.partials.sidebar-instructor')
        @else
            @include('layouts.partials.sidebar-student')
        @endif
    </nav>
    <div class="sidebar-footer">&copy; {{ date('Y') }} Student Welfare Foundation</div>
</aside>

{{-- ── MOBILE OFFCANVAS SIDEBAR ─────────────────────────── --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNav" style="width:272px;border:none">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-logo">SWF</div>
            <div>
                <div class="brand-title">SWF Portal</div>
                <div class="brand-sub">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="mobile-sidebar-inner">
            <nav>
                @if(auth()->user()->role === 'admin')
                    @include('layouts.partials.sidebar-admin')
                @elseif(auth()->user()->role === 'instructor')
                    @include('layouts.partials.sidebar-instructor')
                @else
                    @include('layouts.partials.sidebar-student')
                @endif
            </nav>
            <div class="mobile-footer">&copy; {{ date('Y') }} Student Welfare Foundation</div>
        </div>
    </div>
</div>

{{-- ── MAIN CONTENT WRAPPER ─────────────────────────────── --}}
<div class="main-content">
    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            {{-- Mobile hamburger --}}
            <button class="btn btn-sm btn-light hamburger border-0 p-1"
                    data-bs-toggle="offcanvas" data-bs-target="#mobileNav"
                    style="width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title">@yield('title')</span>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <div class="user-avatar d-none d-sm-flex">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span class="small text-muted d-none d-lg-inline" style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ auth()->user()->name }}</span>
            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-light border" style="padding:5px 8px;border-radius:7px" title="Profile">
                <i class="bi bi-person"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger" style="padding:5px 8px;border-radius:7px" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div class="content-area pb-0 pt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-start gap-2 shadow-sm border-0 mb-3">
            <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 shadow-sm border-0 mb-3">
            <i class="bi bi-exclamation-circle-fill mt-1 flex-shrink-0"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    <div class="content-area pt-2">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="text-center py-3 mt-2" style="border-top:1px solid #e2e8f0;font-size:.72rem;color:#94a3b8">
        &copy; {{ date('Y') }} Student Welfare Foundation &mdash;
        <a href="https://wa.me/923148379859" class="text-success text-decoration-none"><i class="bi bi-whatsapp"></i> +92 314 8379859</a>
        &mdash;
        <a href="mailto:swfhelpers@gmail.com" class="text-muted text-decoration-none"><i class="bi bi-envelope"></i> swfhelpers@gmail.com</a>
    </footer>
</div>

@else
<div class="container py-4">@yield('content')</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>