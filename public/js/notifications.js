// Real-time notifications using Laravel Reverb

let echo = null;

function loadNotifications() {
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
        const container = document.getElementById('notificationList');
        const countSpan = document.getElementById('notificationCount');
        
        if (!container) return;
        
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
    .catch(error => console.error('Error loading notifications:', error));
}

function initializeEcho() {
    if (typeof window.Echo !== 'undefined' && !echo) {
        const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        
        if (userId) {
            echo = window.Echo;
            echo.private(`notifications.${userId}`)
                .listen('.new-notification', (data) => {
                    loadNotifications();
                });
            console.log('WebSocket connected');
        }
    } else {
        setTimeout(initializeEcho, 500);
    }
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
    // Load notifications when dropdown is opened (Bootstrap event)
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.addEventListener('shown.bs.dropdown', function() {
            loadNotifications();
        });
    }
    
    // Initial load
    loadNotifications();
    
    // WebSocket connection
    setTimeout(initializeEcho, 500);
    
    // Mark all as read button
    const markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }
    
    // Mark single as read on click
    document.addEventListener('click', function(e) {
        const item = e.target.closest('.notification-item');
        if (item && item.classList && item.classList.contains('unread')) {
            const id = item.getAttribute('data-id');
            if (id) markAsRead(id);
        }
    });
});