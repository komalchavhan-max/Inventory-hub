<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('employee.dashboard') }}" class="brand">
        <span class="brand-mark"><i class="bi bi-boxes"></i></span>
        <div>
            <div class="brand-name">Inventory Hub</div>
            <div class="brand-tag">Employee Portal</div>
        </div>
    </a>

    <nav class="sidebar-menu">
        <div class="nav-section">Menu</div>
        <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>

        <div class="nav-section">Requests</div>
        <a href="{{ route('employee.requests.equipment.form') }}" class="nav-link {{ request()->routeIs('employee.requests.equipment.*') ? 'active' : '' }}">
            <i class="bi bi-laptop"></i><span>Request Equipment</span>
        </a>
        <a href="{{ route('employee.requests.exchange.form') }}" class="nav-link {{ request()->routeIs('employee.requests.exchange.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-repeat"></i><span>Exchange Equipment</span>
        </a>
        <a href="{{ route('employee.requests.repair.form') }}" class="nav-link {{ request()->routeIs('employee.requests.repair.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i><span>Report Repair</span>
        </a>
        <a href="{{ route('employee.requests.return.form') }}" class="nav-link {{ request()->routeIs('employee.requests.return.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-left"></i><span>Return Equipment</span>
        </a>

        <div class="nav-section">History</div>
        <a href="{{ route('employee.my-requests') }}" class="nav-link {{ request()->routeIs('employee.my-requests') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i><span>My Requests</span>
        </a>
    </nav>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>