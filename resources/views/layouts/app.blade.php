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
            --swf-blue-mid:  #1d4ed8;
            --swf-teal:      #0ea5e9;
            --swf-green:     #059669;
            --swf-sidebar-w: 248px;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #0f172a; }

        /* ── SIDEBAR ───────────────────────────────────────── */
        .sidebar {
            width: var(--swf-sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--swf-blue-dark) 0%, var(--swf-blue) 60%, #1d4ed8 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(30,58,138,.18);
        }

        /* ── BRAND / LOGO AREA ─────────────────────────────── */
        .sidebar .brand {
            padding: 1.1rem 1.1rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.12);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 900;
            color: var(--swf-blue);
            letter-spacing: -.5px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .sidebar .brand-text { line-height: 1.2; }
        .sidebar .brand-title {
            font-size: .9rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: .3px;
        }
        .sidebar .brand-sub {
            font-size: .65rem;
            color: rgba(255,255,255,.55);
            font-weight: 500;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        /* ── NAV LINKS ─────────────────────────────────────── */
        .sidebar nav { flex: 1; padding: .5rem 0; overflow-y: auto; }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            border-radius: 8px;
            margin: 1px 10px;
            padding: 9px 14px;
            font-size: .85rem;
            font-weight: 500;
            transition: all .18s;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.18);
            color: #fff;
            font-weight: 700;
            box-shadow: inset 3px 0 0 var(--swf-teal);
        }
        .sidebar .nav-section-label {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,.35);
            padding: .6rem 1.4rem .2rem;
            margin-top: .25rem;
        }

        /* ── SIDEBAR FOOTER ────────────────────────────────── */
        .sidebar .sidebar-footer {
            padding: .75rem 1rem;
            border-top: 1px solid rgba(255,255,255,.1);
            font-size: .72rem;
            color: rgba(255,255,255,.4);
            text-align: center;
        }

        /* ── MAIN CONTENT ──────────────────────────────────── */
        .main-content { margin-left: var(--swf-sidebar-w); min-height: 100vh; }

        /* ── TOPBAR ────────────────────────────────────────── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .65rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .topbar .page-title {
            font-size: .9rem;
            font-weight: 700;
            color: #374151;
        }
        .topbar .user-pill {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .82rem;
        }
        .topbar .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--swf-blue), var(--swf-teal));
            color: #fff;
            font-weight: 700;
            font-size: .8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── CONTENT AREA ──────────────────────────────────── */
        .content-area { padding: 1.5rem; }

        /* ── RESPONSIVE ────────────────────────────────────── */
        @media (max-width: 991px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }

        @stack('extra-styles')
    </style>
    @stack('styles')
</head>
<body>

@auth
<aside class="sidebar">
    {{-- Brand Area --}}
    <div class="brand">
        <div class="brand-logo" style="background:#fff; border-radius:8px; padding:4px;">
            <img src="http://localhost/testformbuilder/public/images/swf-logo.svg" 
     alt="SWF Logo" 
     style="width:32px; height:32px; object-fit:contain; display:block;">
                 
        </div>
        <div class="brand-text">
            <div class="brand-title">SWF Portal</div>
            <div class="brand-sub">{{ ucfirst(auth()->user()->role) }} Panel</div>
        </div>
    </div>
    {{-- Navigation --}}
    <nav>
        @if(auth()->user()->role === 'admin')
            @include('layouts.partials.sidebar-admin')
        @elseif(auth()->user()->role === 'instructor')
            @include('layouts.partials.sidebar-instructor')
        @else
            @include('layouts.partials.sidebar-student')
        @endif
    </nav>

    {{-- Sidebar footer --}}
    <div class="sidebar-footer">
        &copy; {{ date('Y') }} Student Welfare Foundation
    </div>
</aside>

<div class="main-content">
    {{-- Topbar --}}
    <div class="topbar">
        <span class="page-title">@yield('title')</span>
        <div class="user-pill">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span class="text-muted d-none d-md-inline">{{ auth()->user()->name }}</span>
            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Profile">
                <i class="bi bi-person"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Content --}}
    <div class="content-area">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible shadow-sm border-0 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible shadow-sm border-0 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </div>
</div>

@else
{{-- Guest layout --}}
<div class="container py-4">@yield('content')</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>