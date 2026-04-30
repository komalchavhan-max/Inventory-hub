// notifications.js - Fixed version with proper stop conditions
let notificationInterval = null;
let pollingCount = 0;
let isPageVisible = true;
let isUserActive = true;
let inactiveTimer = null;
const MAX_POLLING = 30;        // Stop after 30 polls (15 minutes)
const INACTIVE_TIMEOUT = 300000; // 5 minutes of inactivity

function loadNotifications() {
    if (!isPageVisible || !isUserActive) {
        console.log('Polling skipped - page hidden or user inactive');
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) return;
    
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
            } else if (notif.status === 'Info') {
                statusClass = 'info';
                statusText = 'ℹ Info';
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
    });
}

function markAsRead(notificationId) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch('/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: notificationId })
    })
    .then(response => response.json())
    .then(() => loadNotifications())
    .catch(error => console.error('Error marking as read:', error));
}

function markAllAsRead() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
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

function resetInactiveTimer() {
    isUserActive = true;
    if (inactiveTimer) {
        clearTimeout(inactiveTimer);
    }
    inactiveTimer = setTimeout(() => {
        isUserActive = false;
        console.log('User inactive - polling stopped');
    }, INACTIVE_TIMEOUT);
}

function startPolling() {
    if (notificationInterval) {            // Clear any existing interval
        clearInterval(notificationInterval);
        notificationInterval = null;
    }
    
    pollingCount = 0;
    isPageVisible = true;
    isUserActive = true;
    
    notificationInterval = setInterval(function() {
        pollingCount++;
        
        if (pollingCount >= MAX_POLLING) {     // Stop after maximum polls
            clearInterval(notificationInterval);
            notificationInterval = null;
            console.log('Notifications polling stopped (max limit of ' + MAX_POLLING + ' polls reached)');
            return;
        }
        
        loadNotifications();
    }, 30000);
    
    console.log('Notifications polling started - will stop after ' + MAX_POLLING + ' polls');
}

function stopPolling() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
        notificationInterval = null;
        console.log('Notifications polling stopped');
    }
}

document.addEventListener('mousemove', resetInactiveTimer);    // Track user activity
document.addEventListener('keypress', resetInactiveTimer);
document.addEventListener('click', resetInactiveTimer);
document.addEventListener('scroll', resetInactiveTimer);

document.addEventListener('DOMContentLoaded', function() {      // Initialize
    loadNotifications();
    startPolling();
    resetInactiveTimer();
    
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
    
    document.addEventListener('visibilitychange', function() {    
        isPageVisible = !document.hidden;
        if (document.hidden) {
            stopPolling();
        } else {
            startPolling();
            loadNotifications();
        }
    });
});

window.addEventListener('beforeunload', function() {    // Stop polling when page is closed
    stopPolling();
});