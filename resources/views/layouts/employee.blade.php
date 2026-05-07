<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Dashboard') - Inventory Hub</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.svg') }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/employee-style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/employee-layout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
    <link href="{{ asset('css/my-requests.css') }}" rel="stylesheet">
    <link href="{{ asset('css/requests.css') }}" rel="stylesheet">
    
    <!-- Vite (for Reverb/Echo) -->
    @vite(['resources/js/app.js'])
    
    @stack('styles')
</head>
<body>
    @include('layouts.partials.employee-sidebar')

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
                @php
                    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4f46e5&color=fff&rounded=true&size=64';
                @endphp
                <button type="button" class="user-menu dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ $avatarUrl }}" alt="">
                    <span class="name">{{ Auth::user()->name }}</span>
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

        @yield('content')
    </div>

    <!-- 1. jQuery (required by Bootstrap and DataTables) -->
    <script src="{{ asset('src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    
    <!-- 2. Bootstrap JS (requires jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 3. Iconify -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    
    <!-- 4. DataTables JS (requires jQuery) -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <!-- 5. Sidebar Toggle Script -->
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
    
    <!-- 6. Custom Scripts (after all dependencies) -->
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    
    <!-- 7. Stack Scripts -->
    @stack('scripts')

       
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileBtn = document.querySelector('.user-menu');
            if (profileBtn) {
                profileBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                        dropdownMenu.classList.toggle('show');
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(function(dropdown) {
                        dropdown.classList.remove('show');
                    });
                }
            });
        });
    </script>

</body>
</html>