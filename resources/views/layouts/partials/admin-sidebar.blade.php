<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand">
        <span class="brand-mark"><i class="bi bi-boxes"></i></span>
        <div>
            <div class="brand-name">Inventory Hub</div>
            <div class="brand-tag">Admin Portal</div>
        </div>
    </a>

    <nav class="sidebar-menu">
        <div class="nav-section">Home</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>

        <div class="nav-section">Inventory</div>
        <a href="{{ route('admin.equipment.index') }}" class="nav-link {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
            <i class="bi bi-laptop"></i><span>Equipment</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i><span>Categories</span>
        </a>

        <div class="nav-section">Requests</div>
        <a href="{{ route('admin.requests.equipment') }}" class="nav-link {{ request()->routeIs('admin.requests.equipment*') ? 'active' : '' }}">
            <i class="bi bi-inbox"></i><span>Equipment Requests</span>
        </a>
        <a href="{{ route('admin.requests.exchange') }}" class="nav-link {{ request()->routeIs('admin.requests.exchange*') ? 'active' : '' }}">
            <i class="bi bi-arrow-repeat"></i><span>Exchange Requests</span>
        </a>
        <a href="{{ route('admin.requests.repair') }}" class="nav-link {{ request()->routeIs('admin.requests.repair*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i><span>Repair Requests</span>
        </a>
        <a href="{{ route('admin.requests.return') }}" class="nav-link {{ request()->routeIs('admin.requests.return*') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-left"></i><span>Return Requests</span>
        </a>

        <a href="{{ route('admin.maintenance-logs.index') }}" class="nav-link {{ request()->routeIs('admin.maintenance-logs*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark-fill"></i><span>Repair Logs</span>
        </a>
    </nav>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>