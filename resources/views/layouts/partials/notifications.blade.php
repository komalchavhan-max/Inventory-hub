<li class="nav-item dropdown">
    <a class="nav-link position-relative p-2 dropdown-toggle" href="#" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        <span id="notificationCount" class="notification-badge" style="display: none; position: absolute; top: 0; right: 0; background-color: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold; min-width: 18px; text-align: center;"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 500px; overflow-y: auto;">
        <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
            <h6 class="mb-0">Notifications</h6>
            <a href="#" id="markAllReadBtn" class="small text-primary text-decoration-none">Mark all as read</a>
        </div>
        <div id="notificationList" style="min-height: 100px;">
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="dropdown-footer text-center border-top py-2 bg-light">
            <a href="{{ url('/notifications') }}" class="small text-decoration-none">View all notifications</a>
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
    line-height: 1.4;
    color: #333;
}
.notification-item .notification-time {
    font-size: 11px;
    color: #999;
    margin-top: 5px;
}
.notification-item .badge {
    font-size: 10px;
    padding: 3px 8px;
}
</style>