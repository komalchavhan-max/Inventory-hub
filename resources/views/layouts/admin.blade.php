<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Inventory Hub</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('src/assets/images/logos/favicon.png') }}" />
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            background: #1b293b;
            color: white;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        
        .sidebar .logo img {
            max-width: 120px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background: #b3c5d6;
            color: white;
        }
        
        .sidebar .nav-link.active {
            background: #0085db;
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .sidebar .nav-small-cap {
            padding: 10px 20px;
            color: #7f8c8d;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            border-radius: 10px;
            padding: 10px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        /* Cards */
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eef2f6;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -260px;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="{{ asset('src/assets/images/logos/logo.svg') }}" alt="Logo" width="120">
        </div>
        
        <div class="sidebar-menu">
            <div class="nav-small-cap">HOME</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            <div class="nav-small-cap mt-3">INVENTORY</div>
            <a href="{{ route('admin.equipment.index') }}" class="nav-link">
                <i class="bi bi-laptop"></i> Equipment
            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                <i class="bi bi-tag"></i> Categories
            </a>          
                    
            <div class="nav-small-cap mt-3">REQUESTS</div>
            <a href="{{ route('admin.requests.equipment') }}" class="nav-link">
                <i class="bi bi-inbox"></i> Equipment Requests
            </a>
            <a href="{{ route('admin.requests.exchange') }}" class="nav-link">
                <i class="bi bi-arrow-repeat"></i> Exchange Requests
            </a>
            <a href="{{ route('admin.requests.repair') }}" class="nav-link">
                <i class="bi bi-tools"></i> Repair Requests
            </a>
            <a href="{{ route('admin.requests.return') }}" class="nav-link">
                <i class="bi bi-arrow-return-left"></i> Return Requests
            </a>
            <a href="{{ route('admin.maintenance-logs.index') }}" class="nav-link">
                <i class="bi bi-tools"></i> Maintenance Logs
            </a>
        </div>
    </div>
    
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            @include('layouts.partials.notifications')    
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <img src="{{ asset('src/assets/images/profile/user1.jpg') }}" alt="" width="30" height="30" class="rounded-circle me-2">
                    {{ Auth::user()->name }}
                </button>
 
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><hr class="dropdown-divider"></li>
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Please fix the following issues:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
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
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>