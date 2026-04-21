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
                                            <img src="{{ asset('spike-bootstrap-free-v2/src/assets/images/profile/user1.jpg') }}" alt="" width="35" height="35" class="rounded-circle">
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
                                        @include('layouts.partials.notifications')
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
    <!-- Notification Scripts -->
    <script>
        function loadNotifications() {                    // Function to load notifications
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/notifications/fetch', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('notificationList');
                const countSpan = document.getElementById('notificationCount');
                
                if (!container) return;
                
                if (data.notifications && data.notifications.length === 0) {
                    container.innerHTML = '<div class="text-center py-3 text-muted">No notifications</div>';
                    if (countSpan) countSpan.style.display = 'none';
                    return;
                }
                
                let html = '';
                (data.notifications || []).forEach(notif => {
                    let statusClass = notif.status === 'Approved' ? 'success' : (notif.status === 'Rejected' ? 'danger' : 'info');
                    html += `
                        <div class="notification-item ${notif.is_read ? '' : 'unread'}" data-id="${notif.id}">
                            <div class="d-flex justify-content-between">
                                <div class="notification-message">${escapeHtml(notif.message)}</div>
                                <span class="badge bg-${statusClass}">${notif.status}</span>
                            </div>
                            <div class="notification-time">${notif.created_at || 'Just now'}</div>
                        </div>
                    `;
                });
                container.innerHTML = html;
                
                if (data.unread_count > 0 && countSpan) {
                    countSpan.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    countSpan.style.display = 'inline-block';
                } else if (countSpan) {
                    countSpan.style.display = 'none';
                }
            })
            .catch(error => console.error('Error loading notifications:', error));
        }
        
        function markAsRead(id) {             // Function to mark notification as read
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({id: id})
            })
            .then(response => response.json())
            .then(() => loadNotifications());
        }
        
        function markAllRead() {              // Function to mark all as read
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(() => loadNotifications());
        }
        
        function escapeHtml(text) {            // Escape HTML to prevent XSS
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        document.addEventListener('DOMContentLoaded', function() {             // Initialize when page loads
            loadNotifications();
            
            const markAllBtn = document.getElementById('markAllRead');          // Mark all as read button
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    markAllRead();
                });
            }

            document.addEventListener('click', function(e) {                // Handle notification click
                const item = e.target.closest('.notification-item');
                if (item && item.classList.contains('unread')) {
                    const id = item.dataset.id;
                    if (id) markAsRead(id);
                }
            });
        });
        
        setInterval(loadNotifications, 30000);           // Refresh every 30 seconds
    </script>
    @stack('scripts')
</body>
</html>