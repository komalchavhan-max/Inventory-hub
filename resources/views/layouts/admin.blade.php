<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Helpers\TitleHelper::getTitle() }} - Inventory Hub</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.svg') }}" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    
    <!-- Validation CSS -->
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Datepicker CSS -->
    <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">

    <!-- Form CSS -->
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Include Sidebar -->
    @include('layouts.partials.admin-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
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
                @php
                    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4f46e5&color=fff&rounded=true&size=64';
                @endphp
                <button type="button" class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ $avatarUrl }}" alt="">
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

        <!-- Flash Messages -->
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

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- Scripts -->
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
    <script src="{{ asset('js/validation.js') }}"></script>
</body>
</html>