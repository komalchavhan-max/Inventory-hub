<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Dashboard') - Inventory Hub</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('src/assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('src/assets/css/styles.min.css') }}" />
    @stack('styles')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .left-sidebar {
        top: 0 !important;
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    .page-wrapper {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    .brand-logo {
        margin-top: 0 !important;
        padding-top: 15px !important;
    }

    .body-wrapper-inner {
        padding-top: 0 !important;
    }

    .container-fluid {
        padding-top: 15px !important;
    }

    .app-header {
        margin-bottom: 0 !important;
        padding: 10px 0 !important;
    }

    .body-wrapper {
        padding-top: 10 !important;
    }
</style>
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        
        <!-- Sidebar Start (Simplified for Employee) -->
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route('employee.dashboard') }}" class="text-nowrap logo-img">
                        <img src="{{ asset('src/assets/images/logos/logo.svg') }}" alt="Logo" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">Menu</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link primary-hover-bg" href="{{ route('employee.dashboard') }}" aria-expanded="false">
                                <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link primary-hover-bg" href="{{ route('employee.requests.equipment.form') }}" aria-expanded="false">
                                <iconify-icon icon="solar:computer-line-duotone"></iconify-icon>
                                <span class="hide-menu">Request Equipment</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link primary-hover-bg" href="{{ route('employee.requests.exchange.form') }}" aria-expanded="false">
                                <iconify-icon icon="solar:repeat-line-duotone"></iconify-icon>
                                <span class="hide-menu">Exchange Equipment</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link primary-hover-bg" href="{{ route('employee.requests.repair.form') }}" aria-expanded="false">
                                <iconify-icon icon="solar:tools-line-duotone"></iconify-icon>
                                <span class="hide-menu">Report Repair</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link primary-hover-bg" href="{{ route('employee.requests.return.form') }}" aria-expanded="false">
                                <iconify-icon icon="solar:undo-left-line-duotone"></iconify-icon>
                                <span class="hide-menu">Return Equipment</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link primary-hover-bg" href="{{ route('employee.my-requests') }}" aria-expanded="false">
                                <iconify-icon icon="solar:list-line-duotone"></iconify-icon>
                                <span class="hide-menu">My Requests</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- Sidebar End -->
        
        <!-- Main wrapper -->
        <div class="body-wrapper">
            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    <!-- Header Start -->
                    <header class="app-header">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <ul class="navbar-nav">
                                <li class="nav-item d-block d-xl-none">
                                    <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                                        <i class="ti ti-menu-2"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="{{ asset('src/assets/images/profile/user1.jpg') }}" alt="" width="35" height="35" class="rounded-circle">
                                            <span class="ms-2 d-none d-sm-inline">{{ Auth::user()->name }}</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                            <div class="message-body">
                                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <i class="ti ti-logout fs-6"></i> Logout
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </header>
                    <!-- Header End -->
                    
                    <!-- Page Content -->
                    @yield('content')
                    
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('src/assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('src/assets/js/app.min.js') }}"></script>
    <script src="{{ asset('src/assets/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
        <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    @stack('scripts')
</body>
</html>