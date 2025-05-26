// Browser notification script for ProGymHub
document.addEventListener('DOMContentLoaded', function() {
    // Check if browser supports notifications
    if (!('Notification' in window)) {
        console.log('This browser does not support desktop notifications');
        return;
    }

    // Request permission for notifications if not already granted
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        document.getElementById('enable-notifications-btn')?.addEventListener('click', function() {
            requestNotificationPermission();
        });
    } else if (Notification.permission === 'granted') {
        // Hide the enable notifications button if permission is already granted
        const enableBtn = document.getElementById('enable-notifications-btn');
        if (enableBtn) {
            enableBtn.style.display = 'none';
        }
    }

    // Check for new notifications every 30 seconds
    checkForNewNotifications();
    setInterval(checkForNewNotifications, 30000);
});

// Function to request notification permission
function requestNotificationPermission() {
    Notification.requestPermission().then(function(permission) {
        if (permission === 'granted') {
            console.log('Notification permission granted');
            // Hide the enable notifications button once permission is granted
            const enableBtn = document.getElementById('enable-notifications-btn');
            if (enableBtn) {
                enableBtn.style.display = 'none';
            }
            // Show a test notification
            new Notification('ProGymHub Notifications Enabled', {
                body: 'You will now receive notifications from ProGymHub.',
                icon: '/assets/img/favicon.png'
            });
        } else {
            console.log('Notification permission denied');
        }
    });
}

// Function to check for new notifications via AJAX
function checkForNewNotifications() {
    if (Notification.permission !== 'granted') {
        return;
    }
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Make AJAX request to get new notifications
    fetch('/dashboard/notifications/check-new', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.hasNew && data.notifications.length > 0) {
            // Show browser notification for each new notification
            data.notifications.forEach(notification => {
                showBrowserNotification(notification);
            });
            
            // Update the notification count in the UI
            updateNotificationCount(data.count);
            
            // Refresh the notification dropdown if it exists
            refreshNotificationDropdown();
        }
    })
    .catch(error => console.error('Error checking for notifications:', error));
}

// Function to refresh the notification dropdown content
function refreshNotificationDropdown() {
    const dropdown = document.querySelector('.notifications-menu');
    if (dropdown) {
        // Get the CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Fetch the latest unread notifications
        fetch('/dashboard/notifications/dropdown-content', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                // Update the dropdown content
                dropdown.innerHTML = data.html;
                
                // Re-initialize notification click handlers
                setupNotificationClickHandlers();
            }
        })
        .catch(error => console.error('Error refreshing notifications dropdown:', error));
    }
}

// Function to show browser notification
function showBrowserNotification(notification) {
    const notif = new Notification(notification.title, {
        body: notification.message,
        icon: '/assets/img/favicon.png',
        data: {
            id: notification.id,
            url: `/dashboard/notifications/read/${notification.id}`
        }
    });

    // Handle click on notification
    notif.onclick = function(event) {
        event.preventDefault();
        window.open(notif.data.url, '_blank');
    };
}

// Function to update notification count in the UI
function updateNotificationCount(count) {
    const countBadge = document.querySelector('.notification-badge');
    if (countBadge) {
        if (count > 0) {
            countBadge.textContent = count;
            countBadge.style.display = 'inline-block';
        } else {
            countBadge.style.display = 'none';
        }
    }
}

// Add event listener for notification items in dropdown
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification items click handlers
    setupNotificationClickHandlers();
});

// Setup click handlers for notification items
function setupNotificationClickHandlers() {
    const notificationItems = document.querySelectorAll('.notification-item');
    
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.getAttribute('data-id');
            const url = this.getAttribute('data-url');
            
            // Mark notification as read via AJAX
            markNotificationAsRead(notificationId, url, this);
        });
    });
}

// Mark notification as read and handle UI updates
function markNotificationAsRead(notificationId, url, element) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.redirected) {
            // If the server redirects, follow the redirect
            window.location.href = response.url;
        } else {
            return response.json().then(data => {
                // Complete removal of the notification item from dropdown
                if (element) {
                    // If there's a divider after this element, remove it too
                    const nextElement = element.nextElementSibling;
                    if (nextElement && nextElement.tagName === 'HR') {
                        nextElement.remove();
                    } else {
                        // If there's a divider before this element, remove it instead
                        const prevElement = element.previousElementSibling;
                        if (prevElement && prevElement.tagName === 'HR') {
                            prevElement.remove();
                        }
                    }
                    
                    // Remove the notification item itself
                    element.remove();
                    
                    // Update the unread count badge
                    updateUnreadCountAfterRead();
                    
                    // Check if we need to show "No notifications" message
                    checkForEmptyNotificationList();
                }
            });
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

// Update the unread notification count after marking one as read
function updateUnreadCountAfterRead() {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        const currentCount = parseInt(badge.textContent) || 0;
        if (currentCount > 0) {
            const newCount = currentCount - 1;
            if (newCount > 0) {
                badge.textContent = newCount;
            } else {
                badge.style.display = 'none';
            }
        }
    }
}

// Check if the notification list is empty and show a message if needed
function checkForEmptyNotificationList() {
    const remainingNotifications = document.querySelectorAll('.notification-item');
    if (remainingNotifications.length === 0) {
        const dropdownMenu = document.querySelector('.notifications-menu');
        if (dropdownMenu) {
            const noNotificationsMsg = document.createElement('a');
            noNotificationsMsg.href = '#';
            noNotificationsMsg.className = 'dropdown-item';
            noNotificationsMsg.innerHTML = '<h6 class="fw-normal mb-0">No unread notifications</h6>';
            
            // Insert before the divider
            const divider = dropdownMenu.querySelector('hr.dropdown-divider');
            if (divider) {
                dropdownMenu.insertBefore(noNotificationsMsg, divider);
            } else {
                // If no divider, insert at the beginning
                const firstChild = dropdownMenu.firstChild;
                if (firstChild) {
                    dropdownMenu.insertBefore(noNotificationsMsg, firstChild);
                } else {
                    dropdownMenu.appendChild(noNotificationsMsg);
                }
            }
        }
    }
}
