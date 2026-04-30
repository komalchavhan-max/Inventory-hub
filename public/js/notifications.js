// Real-time notifications using Laravel Reverb (No Polling)

let echo = null;

function initializeEcho() {
    if (typeof window.Echo !== 'undefined' && !echo) {
        const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        
        if (userId) {
            echo = window.Echo;
            echo.private(`notifications.${userId}`)
                .listen('.new-notification', (data) => {
                    console.log('New notification received:', data);
                    loadNotifications();
                    // Also update count if dropdown is closed
                    updateNotificationCount();
                });
            console.log('WebSocket connected - Real-time notifications enabled');
        }
    } else {
        console.log('Waiting for Echo to initialize...');
        setTimeout(initializeEcho, 1000);
    }
}

function updateNotificationCount() {
    fetch('/notifications/fetch', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const countSpan = document.getElementById('notificationCount');
        if (data.unread_count > 0 && countSpan) {
            countSpan.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
            countSpan.style.display = 'block';
        } else if (countSpan) {
            countSpan.style.display = 'none';
        }
    })
    .catch(error => console.error('Error updating count:', error));
}

function loadNotifications() {
    console.log('Loading notifications...');
    
    fetch('/notifications/fetch', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Notifications loaded:', data.notifications?.length || 0, 'items');
        
        const container = document.getElementById('notificationList');
        const countSpan = document.getElementById('notificationCount');
        
        if (!container) {
            console.error('Notification list container not found!');
            return;
        }
        
        if (!data.notifications || data.notifications.length === 0) {
            container.innerHTML = '<div class="text-center py-3 text-muted">No notifications</div>';
            if (countSpan) countSpan.style.display = 'none';
            return;
        }
        
        let html = '';
        data.notifications.forEach(notif => {
            let statusClass = 'secondary';
            let statusText = notif.status;
            if (notif.status === 'Approved') {
                statusClass = 'success';
                statusText = '✓ Approved';
            } else if (notif.status === 'Rejected') {
                statusClass = 'danger';
                statusText = '✗ Rejected';
            } else if (notif.status === 'Completed') {
                statusClass = 'success';
                statusText = '✓ Completed';
            } else if (notif.status === 'Pending') {
                statusClass = 'warning';
                statusText = '⏳ Pending';
            }
            
            const unreadClass = notif.is_read ? '' : 'unread';
            
            html += `<div class="notification-item ${unreadClass}" data-id="${notif.id}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="notification-message flex-grow-1">${escapeHtml(notif.message)}</div>
                            <span class="badge bg-${statusClass} ms-2">${statusText}</span>
                        </div>
                        <div class="notification-time">${notif.created_at || 'Just now'}</div>
                    </div>`;
        });
        container.innerHTML = html;
        
        if (data.unread_count > 0 && countSpan) {
            countSpan.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
            countSpan.style.display = 'block';
        } else if (countSpan) {
            countSpan.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error loading notifications:', error);
        const container = document.getElementById('notificationList');
        if (container) {
            container.innerHTML = '<div class="text-center py-3 text-danger">Failed to load notifications</div>';
        }
    });
}

function markAsRead(notificationId) {
    fetch('/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: notificationId })
    })
    .then(response => response.json())
    .then(() => loadNotifications())
    .catch(error => console.error('Error marking as read:', error));
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(() => loadNotifications())
    .catch(error => console.error('Error marking all as read:', error));
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing notifications...');
    
    // Load notifications when dropdown is opened
    const dropdownToggle = document.getElementById('notificationDropdown');
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function() {
            loadNotifications();
        });
    }
    
    // Initial load
    loadNotifications();
    
    // Initialize WebSocket for real-time
    setTimeout(initializeEcho, 500);
    
    const markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }
    
    document.addEventListener('click', function(e) {
        const item = e.target.closest('.notification-item');
        if (item && item.classList && item.classList.contains('unread')) {
            const id = item.getAttribute('data-id');
            if (id) markAsRead(id);
        }
    });
});

// Force dropdown to work
document.addEventListener('DOMContentLoaded', function() {
    const bellIcon = document.getElementById('notificationDropdown');
    if (bellIcon) {
        bellIcon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdown = document.querySelector('.dropdown-menu');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        });
    }
});