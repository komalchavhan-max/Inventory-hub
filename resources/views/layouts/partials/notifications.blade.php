<li class="nav-item dropdown">
    <a class="nav-link position-relative" href="#" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell"></i>
        <span class="notification-badge" id="notificationCount" style="display: none; position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px;"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 500px; overflow-y: auto;">
        <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <h6 class="mb-0">Notifications</h6>
            <a href="#" id="markAllRead" class="small text-primary">Mark all as read</a>
        </div>
        <div id="notificationList" style="min-height: 100px;">
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="dropdown-footer text-center border-top py-2">
            <a href="{{ url('/notifications') }}" class="small">View all notifications</a>
        </div>
    </div>
</li>

<style>
    .notification-item {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background 0.2s;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .notification-item.unread {
        background-color: #e3f2fd;
    }
    .notification-item .notification-message {
        font-size: 13px;
        margin-bottom: 5px;
        word-break: break-word;
    }
    .notification-item .notification-time {
        font-size: 11px;
        color: #999;
    }
    .notification-item .badge {
        font-size: 10px;
    }
    .dropdown-header, .dropdown-footer {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .dropdown-footer {
        bottom: 0;
        top: auto;
    }
</style>