<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Inventory Hub</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('src/assets/images/logos/favicon.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --ih-primary: #4f46e5;
            --ih-primary-dark: #4338ca;
            --ih-primary-light: #eef2ff;
            --ih-success: #10b981;
            --ih-success-light: #d1fae5;
            --ih-warning: #f59e0b;
            --ih-warning-light: #fef3c7;
            --ih-danger: #ef4444;
            --ih-danger-light: #fee2e2;
            --ih-info: #0ea5e9;
            --ih-info-light: #e0f2fe;
            --ih-bg: #f5f7fb;
            --ih-surface: #ffffff;
            --ih-border: #e5e7eb;
            --ih-border-soft: #eef0f4;
            --ih-text: #0f172a;
            --ih-text-muted: #64748b;
            --ih-sidebar-bg: #0f172a;
            --ih-sidebar-muted: #94a3b8;
            --ih-sidebar-hover: rgba(255,255,255,0.06);
            --ih-sidebar-active: rgba(99,102,241,0.18);
            --ih-sidebar-width: 260px;
            --ih-shadow-sm: 0 1px 2px rgba(15,23,42,0.04);
            --ih-shadow: 0 1px 3px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04);
            --ih-radius: 14px;
            --ih-radius-sm: 10px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: var(--ih-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--ih-text);
            font-size: 0.9375rem;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--ih-text);
            letter-spacing: -0.01em;
        }

        a { color: var(--ih-primary); text-decoration: none; }
        a:hover { color: var(--ih-primary-dark); }

        /* ---------- Sidebar ---------- */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--ih-sidebar-width);
            background: var(--ih-sidebar-bg);
            color: #cbd5e1;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: transform 0.25s ease;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 10px;
            text-decoration: none;
        }
        .brand-mark {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            display: grid; place-items: center;
            color: #fff;
            font-size: 1.05rem;
            box-shadow: 0 4px 12px -2px rgba(99,102,241,0.5);
            flex-shrink: 0;
        }
        .brand-name { color: #fff; font-weight: 600; font-size: 1rem; line-height: 1.2; }
        .brand-tag {
            color: var(--ih-sidebar-muted);
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .sidebar-menu { padding: 0 12px 24px; flex: 1; }

        .nav-section {
            color: #64748b;
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 600;
            padding: 18px 12px 8px;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 2px;
            transition: background 0.15s ease, color 0.15s ease;
            position: relative;
        }
        .sidebar .nav-link:hover { background: var(--ih-sidebar-hover); color: #fff; }
        .sidebar .nav-link.active { background: var(--ih-sidebar-active); color: #fff; }
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 8px; bottom: 8px;
            width: 3px;
            background: var(--ih-primary);
            border-radius: 0 3px 3px 0;
        }
        .sidebar .nav-link i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            color: var(--ih-sidebar-muted);
            transition: color 0.15s ease;
        }
        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i { color: #fff; }

        /* ---------- Main content ---------- */
        .main-content {
            margin-left: var(--ih-sidebar-width);
            padding: 22px 28px 40px;
            min-height: 100vh;
        }

        /* ---------- Top navbar ---------- */
        .top-navbar {
            background: var(--ih-surface);
            border: 1px solid var(--ih-border-soft);
            border-radius: var(--ih-radius);
            padding: 10px 16px;
            margin-bottom: 22px;
            box-shadow: var(--ih-shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .top-navbar .navbar-nav { flex-direction: row; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }

        .icon-btn {
            width: 40px; height: 40px;
            border-radius: 10px;
            border: 1px solid var(--ih-border);
            background: var(--ih-surface);
            display: inline-grid;
            place-items: center;
            color: var(--ih-text-muted);
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .icon-btn:hover {
            background: var(--ih-primary-light);
            color: var(--ih-primary);
            border-color: var(--ih-primary-light);
        }

        .top-navbar .nav-link.position-relative {
            width: 40px; height: 40px;
            border-radius: 10px;
            border: 1px solid var(--ih-border);
            background: var(--ih-surface);
            display: inline-grid !important;
            place-items: center;
            color: var(--ih-text-muted) !important;
            padding: 0 !important;
            transition: all 0.15s ease;
        }
        .top-navbar .nav-link.position-relative:hover {
            background: var(--ih-primary-light);
            color: var(--ih-primary) !important;
            border-color: var(--ih-primary-light);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 12px 4px 4px;
            border-radius: 10px;
            border: 1px solid var(--ih-border);
            background: var(--ih-surface);
            color: var(--ih-text);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .user-menu:hover { background: var(--ih-primary-light); border-color: #c7d2fe; }
        .user-menu img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
        .user-menu .name { font-weight: 500; font-size: 0.9rem; }

        .dropdown-menu {
            border: 1px solid var(--ih-border);
            border-radius: var(--ih-radius-sm);
            box-shadow: 0 12px 28px -8px rgba(15,23,42,0.15);
            padding: 6px;
            font-size: 0.9rem;
        }
        .dropdown-item { border-radius: 6px; padding: 8px 12px; }
        .dropdown-item:hover { background: var(--ih-primary-light); color: var(--ih-primary); }

        /* ---------- Cards ---------- */
        .card {
            border: 1px solid var(--ih-border-soft);
            border-radius: var(--ih-radius);
            box-shadow: var(--ih-shadow-sm);
            background: var(--ih-surface);
            margin-bottom: 22px;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--ih-border-soft);
            padding: 16px 20px;
            font-weight: 600;
        }
        .card-body { padding: 20px; }

        /* ---------- Welcome banner ---------- */
        .welcome-banner {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff;
            border-radius: var(--ih-radius);
            padding: 26px 28px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -8px rgba(79,70,229,0.4);
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            right: -40px; top: -80px;
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(255,255,255,0.18), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .welcome-banner::after {
            content: '';
            position: absolute;
            left: 30%; bottom: -60px;
            width: 140px; height: 140px;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .welcome-banner h4 { color: #fff; margin: 0 0 4px; font-weight: 600; font-size: 1.35rem; position: relative; }
        .welcome-banner p { margin: 0; opacity: 0.92; font-size: 0.925rem; position: relative; }
        .welcome-banner .welcome-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: rgba(255,255,255,0.18);
            display: grid; place-items: center;
            font-size: 1.7rem;
            backdrop-filter: blur(6px);
            flex-shrink: 0;
            position: relative;
        }

        /* ---------- Stat cards ---------- */
        .stat-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 20px;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: grid; place-items: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 0.78rem;
            color: var(--ih-text-muted);
            font-weight: 500;
            margin-bottom: 4px;
            letter-spacing: 0.01em;
        }
        .stat-value {
            font-size: 1.625rem;
            font-weight: 700;
            color: var(--ih-text);
            margin: 0;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .tint-primary { background: var(--ih-primary-light); color: var(--ih-primary); }
        .tint-success { background: var(--ih-success-light); color: var(--ih-success); }
        .tint-warning { background: var(--ih-warning-light); color: var(--ih-warning); }
        .tint-danger { background: var(--ih-danger-light); color: var(--ih-danger); }
        .tint-info { background: var(--ih-info-light); color: var(--ih-info); }
        .tint-slate { background: #f1f5f9; color: #475569; }

        .text-primary-ih { color: var(--ih-primary) !important; }
        .text-success-ih { color: var(--ih-success) !important; }
        .text-warning-ih { color: var(--ih-warning) !important; }
        .text-danger-ih { color: var(--ih-danger) !important; }

        /* ---------- Tables ---------- */
        .table { margin: 0; font-size: 0.9rem; color: var(--ih-text); }
        .table thead th {
            background: transparent;
            border-bottom: 1px solid var(--ih-border);
            border-top: none;
            color: var(--ih-text-muted);
            font-weight: 500;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 12px 20px;
        }
        .table tbody td {
            padding: 14px 20px;
            vertical-align: middle;
            border-bottom: 1px solid var(--ih-border-soft);
            color: var(--ih-text);
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr { transition: background 0.15s ease; }
        .table tbody tr:hover { background: var(--ih-bg); }

        /* ---------- Badge pill ---------- */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .badge-pill::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* ---------- Pending list ---------- */
        .pending-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid var(--ih-border-soft);
            color: var(--ih-text);
            text-decoration: none;
            transition: background 0.15s ease;
        }
        .pending-item:last-child { border-bottom: none; }
        .pending-item:hover { background: var(--ih-bg); color: var(--ih-text); }
        .pending-item .label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .pending-item .label .label-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: grid; place-items: center;
            font-size: 0.95rem;
        }
        .pending-count {
            min-width: 28px; height: 26px;
            border-radius: 999px;
            background: var(--ih-warning-light);
            color: var(--ih-warning);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0 9px;
        }
        .pending-count.zero { background: #f1f5f9; color: #94a3b8; }

        /* ---------- Action buttons (table row actions) ---------- */
        .action-group {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            white-space: nowrap;
        }
        .action-group form { display: inline-flex; margin: 0; padding: 0; }
        .action-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid transparent;
            background: transparent;
            color: #64748b;
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            font-size: 0.95rem;
            padding: 0;
            line-height: 1;
            text-decoration: none;
            transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
        }
        .action-btn:hover {
            background: var(--ih-bg);
            color: var(--ih-text);
            border-color: var(--ih-border);
        }
        .action-btn.view:hover,
        .action-btn.process:hover,
        .action-btn.message:hover { background: var(--ih-primary-light); color: var(--ih-primary); border-color: #c7d2fe; }
        .action-btn.edit:hover    { background: var(--ih-warning-light); color: var(--ih-warning); border-color: #fde68a; }
        .action-btn.archive:hover,
        .action-btn.delete:hover,
        .action-btn.reject:hover  { background: var(--ih-danger-light);  color: var(--ih-danger);  border-color: #fecaca; }
        .action-btn.restore:hover,
        .action-btn.approve:hover,
        .action-btn.complete:hover{ background: var(--ih-success-light); color: var(--ih-success); border-color: #a7f3d0; }
        .action-btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.18);
        }

        /* ---------- Count chips (header stat summaries) ---------- */
        .count-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .count-chip::before {
            content: '';
            width: 7px; height: 7px;
            border-radius: 50%;
            background: currentColor;
        }
        .count-chip .count-value {
            font-variant-numeric: tabular-nums;
            min-width: 14px;
            text-align: center;
        }

        /* ---------- Buttons ---------- */
        .btn { font-weight: 500; border-radius: 10px; padding: 8px 16px; transition: all 0.15s ease; }
        .btn-primary { background: var(--ih-primary); border-color: var(--ih-primary); }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--ih-primary-dark);
            border-color: var(--ih-primary-dark);
            box-shadow: 0 4px 12px -2px rgba(79,70,229,0.35);
        }
        .btn-outline-primary { color: var(--ih-primary); border-color: var(--ih-primary); }
        .btn-outline-primary:hover { background: var(--ih-primary); border-color: var(--ih-primary); }
        .btn-danger { background: var(--ih-danger); border-color: var(--ih-danger); color: #fff; }
        .btn-danger:hover, .btn-danger:focus {
            background: #dc2626; border-color: #dc2626; color: #fff;
            box-shadow: 0 4px 12px -2px rgba(239,68,68,0.35);
        }
        .btn-secondary {
            background: #fff; border-color: var(--ih-border); color: var(--ih-text);
        }
        .btn-secondary:hover, .btn-secondary:focus {
            background: var(--ih-bg); border-color: var(--ih-border); color: var(--ih-text);
        }
        .btn-sm { padding: 6px 12px; font-size: 0.825rem; border-radius: 8px; }

        /* ---------- Modals ---------- */
        .modal-content {
            border: 1px solid var(--ih-border);
            border-radius: var(--ih-radius);
            box-shadow: 0 24px 48px -12px rgba(15,23,42,0.25);
        }
        .modal-header {
            border-bottom: 1px solid var(--ih-border-soft);
            padding: 16px 20px;
        }
        .modal-body { padding: 18px 20px; }
        .modal-footer {
            border-top: 1px solid var(--ih-border-soft);
            padding: 14px 20px;
            gap: 8px;
        }

        /* ---------- Alerts ---------- */
        .alert { border: 1px solid transparent; border-radius: var(--ih-radius-sm); padding: 12px 16px; }
        .alert-success { background: var(--ih-success-light); color: #065f46; border-color: rgba(16,185,129,0.25); }
        .alert-danger  { background: var(--ih-danger-light);  color: #991b1b; border-color: rgba(239,68,68,0.25); }

        /* ---------- Mobile ---------- */
        .mobile-toggle {
            display: none;
            width: 40px; height: 40px;
            border-radius: 10px;
            border: 1px solid var(--ih-border);
            background: var(--ih-surface);
            color: var(--ih-text);
            align-items: center;
            justify-content: center;
        }
        .sidebar-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.55);
            z-index: 1039;
        }
        .sidebar.show ~ .sidebar-backdrop { display: block; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; }
            .mobile-toggle { display: inline-flex; }
            .welcome-banner { padding: 22px; }
            .welcome-banner .welcome-icon { width: 52px; height: 52px; font-size: 1.4rem; }
        }
        @media (max-width: 575.98px) {
            .welcome-banner { flex-direction: column; align-items: flex-start; }
            .user-menu .name { display: none; }
        }
        
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <span class="brand-mark"><i class="bi bi-boxes"></i></span>
            <div>
                <div class="brand-name">Inventory Hub</div>
                <div class="brand-tag">Admin Panel</div>
            </div>
        </a>

        <nav class="sidebar-menu">
            <div class="nav-section">Home</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </a>

            <div class="nav-section">Inventory</div>
            <a href="{{ route('admin.equipment.index') }}"
               class="nav-link {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                <i class="bi bi-laptop"></i><span>Equipment</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i><span>Categories</span>
            </a>

            <div class="nav-section">Requests</div>
            <a href="{{ route('admin.requests.equipment') }}"
               class="nav-link {{ request()->routeIs('admin.requests.equipment') ? 'active' : '' }}">
                <i class="bi bi-inbox"></i><span>Equipment Requests</span>
            </a>
            <a href="{{ route('admin.requests.exchange') }}"
               class="nav-link {{ request()->routeIs('admin.requests.exchange') ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat"></i><span>Exchange Requests</span>
            </a>
            <a href="{{ route('admin.requests.repair') }}"
               class="nav-link {{ request()->routeIs('admin.requests.repair') ? 'active' : '' }}">
                <i class="bi bi-tools"></i><span>Repair Requests</span>
            </a>
            <a href="{{ route('admin.requests.return') }}"
               class="nav-link {{ request()->routeIs('admin.requests.return') ? 'active' : '' }}">
                <i class="bi bi-arrow-return-left"></i><span>Return Requests</span>
            </a>
            <a href="{{ route('admin.maintenance-logs.index') }}"
               class="nav-link {{ request()->routeIs('admin.maintenance-logs.*') ? 'active' : '' }}">
                <i class="bi bi-wrench-adjustable"></i><span>Maintenance Logs</span>
            </a>
        </nav>
    </aside>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="mobile-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <ul class="navbar-nav">
                    @include('layouts.partials.notifications')
                </ul>
            </div>

            <div class="dropdown">
                <button type="button" class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('src/assets/images/profile/user1.jpg') }}" alt="">
                    <span class="name">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="px-3 py-2 small text-muted">{{ Auth::user()->email }}</li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ asset('src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        (function () {
            var toggle = document.getElementById('sidebarToggle');
            var sidebar = document.getElementById('sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            if (toggle && sidebar) {
                toggle.addEventListener('click', function () { sidebar.classList.toggle('show'); });
            }
            if (backdrop && sidebar) {
                backdrop.addEventListener('click', function () { sidebar.classList.remove('show'); });
            }
        })();
    </script>
    @stack('scripts')
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>
