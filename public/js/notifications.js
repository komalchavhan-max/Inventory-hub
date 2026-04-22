// notifications.js
function loadNotifications(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/notifications/fetch',{
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
        
        if (!data.notifications || data.notifications.length === 0){
            container.innerHTML = '<div class="text-center py-3 text-muted">No notifications</div>';
            if (countSpan) countSpan.style.display = 'none';
            return;
        }
        
        let html = '';
        data.notifications.forEach(notif =>{
            let statusClass = 'secondary';
            let statusText = notif.status;
            if (notif.status === 'Approved'){
                statusClass = 'success';
                statusText = '✓ Approved';
            } else if (notif.status === 'Rejected'){
                statusClass = 'danger';
                statusText = '✗ Rejected';
            } else if (notif.status === 'Info'){
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
        
        if (data.unread_count > 0 && countSpan){
            countSpan.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
            countSpan.style.display = 'block';
        } else if (countSpan) {
            countSpan.style.display = 'none';
        }
    })
    .catch(error =>{
        console.error('Error loading notifications:', error);
        const container = document.getElementById('notificationList');
        if (container) {
            container.innerHTML = '<div class="text-center py-3 text-danger">Error loading notifications</div>';
        }
    });
}

function markAsRead(notificationId){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/notifications/mark-read',{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({id: notificationId})
    })
    .then(response => response.json())
    .then(() => loadNotifications())
    .catch(error => console.error('Error marking as read:', error));
}

function markAllAsRead(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/notifications/mark-all-read',{
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

function escapeHtml(text){
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', function(){        // Initialize when page loads
    loadNotifications();
    
    const markAllBtn = document.getElementById('markAllReadBtn');      // Mark all as read button
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function(e){
            e.preventDefault();
            markAllAsRead();
        });
    }
    
    document.addEventListener('click', function(e){       // Click on notification item
        const item = e.target.closest('.notification-item');
        if (item && item.classList && item.classList.contains('unread')){
            const id = item.getAttribute('data-id');
            if (id) markAsRead(id);
        }
    });
});

setInterval(loadNotifications, 30000);   // Refresh every 30 seconds