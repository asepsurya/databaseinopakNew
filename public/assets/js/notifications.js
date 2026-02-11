/**
 * Notification System JavaScript
 * Provides real-time notification functionality with polling fallback
 */

class NotificationSystem {
    constructor() {
        this.pollingInterval = 30000; // 30 seconds default
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 3000;
        this.currentFilter = 'all';
        this.notifications = [];
        this.unreadCount = 0;
        this.isLoading = false;

        this.init();
    }

    /**
     * Initialize the notification system
     */
    init() {
        this.setupEventListeners();
        this.loadNotifications();
        this.startPolling();
        this.connectEcho();
    }

    /**
     * Setup event listeners for the notification dropdown
     */
    setupEventListeners() {
        // Mark all as read button
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.markAllAsRead();
            });
        }

        // Filter tabs
        document.querySelectorAll('.notification-filter-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                this.filterNotifications(tab.dataset.filter);
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdown = document.querySelector('.notification-dropdown');
            const toggle = document.getElementById('notificationToggle');
            if (dropdown && toggle && !dropdown.contains(e.target) && !toggle.contains(e.target)) {
                this.closeDropdown();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Alt+N to open notifications
            if (e.altKey && e.key === 'n') {
                e.preventDefault();
                this.toggleDropdown();
            }
            // Escape to close
            if (e.key === 'Escape') {
                this.closeDropdown();
            }
        });
    }

    /**
     * Connect to Laravel Echo for real-time updates
     */
    connectEcho() {
        if (typeof Echo === 'undefined') {
            console.log('Laravel Echo not available, using polling only');
            return;
        }

        const userId = this.getUserId();
        if (!userId) {
            console.log('User not authenticated, skipping Echo connection');
            return;
        }

        try {
            Echo.private(`user.${userId}`)
                .listen('NotificationCreated', (e) => {
                    this.handleNewNotification(e);
                })
                .listen('NotificationRead', (e) => {
                    this.handleNotificationRead(e);
                })
                .error((error) => {
                    console.error('Echo connection error:', error);
                    this.handleConnectionError();
                });

            console.log('Connected to notification channel');
        } catch (error) {
            console.error('Failed to connect to Echo:', error);
        }
    }

    /**
     * Get current user ID
     */
    getUserId() {
        const userElement = document.querySelector('[data-user-id]');
        if (userElement) {
            return userElement.dataset.userId;
        }
        return null;
    }

    /**
     * Handle new notification from Echo
     */
    handleNewNotification(event) {
        const notification = event.notification;
        this.notifications.unshift(notification);
        this.unreadCount++;

        this.updateBadge();
        this.prependNotification(notification);
        this.showToast(notification);
        this.playNotificationSound();
    }

    /**
     * Handle notification read event
     */
    handleNotificationRead(event) {
        const notificationId = event.notificationId;
        const notification = this.notifications.find(n => n.id === notificationId);
        if (notification && !notification.read_at) {
            notification.read_at = new Date().toISOString();
            this.unreadCount = Math.max(0, this.unreadCount - 1);
            this.updateBadge();
        }
    }

    /**
     * Handle connection error
     */
    handleConnectionError() {
        this.reconnectAttempts++;
        if (this.reconnectAttempts <= this.maxReconnectAttempts) {
            console.log(`Reconnecting... Attempt ${this.reconnectAttempts}`);
            setTimeout(() => {
                this.connectEcho();
            }, this.reconnectDelay);
        } else {
            console.log('Max reconnect attempts reached, switching to polling only');
            this.startPolling();
        }
    }

    /**
     * Load notifications from server
     */
    async loadNotifications(limit = 10, filter = 'all') {
        if (this.isLoading) return;

        this.isLoading = true;
        const container = document.getElementById('notificationList');

        try {
            const params = new URLSearchParams({
                limit: limit,
                filter: filter
            });

            const response = await fetch(`/notifications/recent?${params}`);
            const data = await response.json();

            if (data.success) {
                this.notifications = data.data;
                this.unreadCount = data.unread_count;
                this.renderNotifications();
                this.updateBadge();
            }
        } catch (error) {
            console.error('Failed to load notifications:', error);
            this.showError('Gagal memuat notifikasi');
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Render notifications to the DOM
     */
    renderNotifications() {
        const container = document.getElementById('notificationList');
        if (!container) return;

        if (this.notifications.length === 0) {
            container.innerHTML = this.getEmptyStateHtml();
            return;
        }

        container.innerHTML = this.notifications.map(notification => this.getNotificationHtml(notification)).join('');
    }

    /**
     * Prepend a new notification to the list
     */
    prependNotification(notification) {
        const container = document.getElementById('notificationList');
        if (!container) return;

        // Remove empty state if present
        const emptyState = container.querySelector('.notification-empty');
        if (emptyState) {
            emptyState.remove();
        }

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = this.getNotificationHtml(notification);
        const newElement = tempDiv.firstElementChild;

        container.insertBefore(newElement, container.firstChild);

        // Limit displayed notifications
        const displayedNotifications = container.querySelectorAll('.notification-item');
        if (displayedNotifications.length > 10) {
            displayedNotifications[displayedNotifications.length - 1].remove();
        }
    }

    /**
     * Get HTML for a single notification
     */
    getNotificationHtml(notification) {
        const isUnread = !notification.read_at;
        const color = notification.data?.color || 'secondary';
        const icon = notification.data?.icon || 'ti ti-bell';
        const title = notification.data?.title || 'Notifikasi';
        const message = notification.data?.message || '';
        const timeAgo = this.formatTimeAgo(notification.created_at);
        const status = notification.data?.status || 'info';
        const url = notification.url || notification.data?.url || '#';

        return `
            <div class="notification-item ${isUnread ? 'unread' : ''}"
                 data-notification-id="${notification.id}"
                 data-status="${status}"
                 data-is-unread="${isUnread}"
                 onclick="notificationSystem.handleNotificationClick('${notification.id}', '${url}')">

                <div class="notification-icon ${color}">
                    <i class="${icon}"></i>
                </div>

                <div class="notification-content">
                    <div class="notification-title">${this.escapeHtml(title)}</div>
                    <div class="notification-message">${this.escapeHtml(message)}</div>
                    <div class="notification-meta">
                        <span class="notification-time">
                            <i class="ti ti-clock"></i>
                            ${timeAgo}
                        </span>
                        <span class="notification-status ${status}">
                            ${this.getStatusIcon(status)}
                            ${this.escapeHtml(status)}
                        </span>
                    </div>
                </div>

                <div class="notification-actions" onclick="event.stopPropagation()">
                    ${isUnread ? `
                        <button class="notification-btn read-btn"
                                onclick="notificationSystem.markAsRead('${notification.id}')"
                                title="Tandai sudah dibaca">
                            <i class="ti ti-check"></i>
                        </button>
                    ` : `
                        <button class="notification-btn"
                                onclick="notificationSystem.markAsUnread('${notification.id}')"
                                title="Tandai belum dibaca">
                            <i class="ti ti-circle"></i>
                        </button>
                    `}
                    <button class="notification-btn delete-btn"
                            onclick="notificationSystem.deleteNotification('${notification.id}')"
                            title="Hapus">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        `;
    }

    /**
     * Get empty state HTML
     */
    getEmptyStateHtml() {
        return `
            <div class="notification-empty">
                <div class="notification-empty-icon">
                    <i class="ti ti-bell-off"></i>
                </div>
                <p class="mb-1">Tidak ada notifikasi</p>
                <span class="fs-xs">Notifikasi baru akan muncul di sini</span>
            </div>
        `;
    }

    /**
     * Get status icon HTML
     */
    getStatusIcon(status) {
        const icons = {
            success: '<i class="ti ti-check"></i>',
            failure: '<i class="ti ti-x"></i>',
            warning: '<i class="ti ti-alert-triangle"></i>',
            info: '<i class="ti ti-info-circle"></i>'
        };
        return icons[status] || icons.info;
    }

    /**
     * Update the unread badge
     */
    updateBadge() {
        const badge = document.getElementById('unreadBadge');
        const countElement = document.querySelector('.notification-header .fs-xs');

        if (badge) {
            badge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            badge.style.display = this.unreadCount > 0 ? 'flex' : 'none';
        }

        if (countElement) {
            countElement.textContent = `${this.unreadCount} belum dibaca`;
        }

        // Update document title with unread count
        this.updateDocumentTitle();
    }

    /**
     * Update document title with notification count
     */
    updateDocumentTitle() {
        if (this.unreadCount > 0) {
            document.title = `(${this.unreadCount}) ${document.title.replace(/^\(\d+\)\s*/, '')}`;
        }
    }

    /**
     * Filter notifications
     */
    filterNotifications(filter) {
        this.currentFilter = filter;

        // Update active tab
        document.querySelectorAll('.notification-filter-tab').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.filter === filter);
        });

        this.loadNotifications(20, filter);
    }

    /**
     * Mark a notification as read
     */
    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification && !notification.read_at) {
                    notification.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                    this.updateBadge();
                    this.updateNotificationItem(notificationId);
                }
            }
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    /**
     * Mark a notification as unread
     */
    async markAsUnread(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/unread`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification && notification.read_at) {
                    notification.read_at = null;
                    this.unreadCount++;
                    this.updateBadge();
                    this.updateNotificationItem(notificationId);
                }
            }
        } catch (error) {
            console.error('Failed to mark notification as unread:', error);
        }
    }

    /**
     * Mark all notifications as read
     */
    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.notifications.forEach(n => {
                    if (!n.read_at) {
                        n.read_at = new Date().toISOString();
                    }
                });
                this.unreadCount = 0;
                this.updateBadge();
                this.renderNotifications();
                this.showToast({
                    data: {
                        message: 'Semua notifikasi telah ditandai dibaca',
                        color: 'success',
                        icon: 'ti ti-check'
                    }
                });
            }
        } catch (error) {
            console.error('Failed to mark all as read:', error);
        }
    }

    /**
     * Delete a notification
     */
    async deleteNotification(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                const index = this.notifications.findIndex(n => n.id === notificationId);
                if (index !== -1) {
                    const notification = this.notifications[index];
                    if (!notification.read_at) {
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                    this.notifications.splice(index, 1);
                    this.updateBadge();

                    // Remove from DOM
                    const element = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (element) {
                        element.remove();
                    }

                    // Show empty state if no notifications
                    if (this.notifications.length === 0) {
                        this.renderNotifications();
                    }
                }
            }
        } catch (error) {
            console.error('Failed to delete notification:', error);
        }
    }

    /**
     * Update a single notification item in the DOM
     */
    updateNotificationItem(notificationId) {
        const element = document.querySelector(`[data-notification-id="${notificationId}"]`);
        if (element) {
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification) {
                const isUnread = !notification.read_at;
                element.classList.toggle('unread', isUnread);
                element.dataset.isUnread = isUnread;

                // Update action buttons
                const actions = element.querySelector('.notification-actions');
                if (actions) {
                    actions.innerHTML = isUnread ? `
                        <button class="notification-btn read-btn"
                                onclick="notificationSystem.markAsRead('${notificationId}')"
                                title="Tandai sudah dibaca">
                            <i class="ti ti-check"></i>
                        </button>
                        <button class="notification-btn delete-btn"
                                onclick="notificationSystem.deleteNotification('${notificationId}')"
                                title="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    ` : `
                        <button class="notification-btn"
                                onclick="notificationSystem.markAsUnread('${notificationId}')"
                                title="Tandai belum dibaca">
                            <i class="ti ti-circle"></i>
                        </button>
                        <button class="notification-btn delete-btn"
                                onclick="notificationSystem.deleteNotification('${notificationId}')"
                                title="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    `;
                }
            }
        }
    }

    /**
     * Handle notification click
     */
    handleNotificationClick(notificationId, url) {
        this.markAsRead(notificationId);
        if (url && url !== '#') {
            window.location.href = url;
        }
    }

    /**
     * Toggle notification dropdown
     */
    toggleDropdown() {
        const dropdown = document.querySelector('.notification-dropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    /**
     * Close notification dropdown
     */
    closeDropdown() {
        const dropdown = document.querySelector('.notification-dropdown');
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    }

    /**
     * Start polling for new notifications
     */
    startPolling() {
        if (this.pollingInterval <= 0) return;

        setInterval(() => {
            this.loadNotifications(10, this.currentFilter);
        }, this.pollingInterval);
    }

    /**
     * Show toast notification
     */
    showToast(notification) {
        // Check if toast function is available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: notification.data?.color || 'info',
                title: notification.data?.title || 'Notifikasi Baru',
                text: notification.data?.message || '',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        } else {
            // Fallback to browser notification if permitted
            this.showBrowserNotification(notification);
        }
    }

    /**
     * Show browser notification
     */
    async showBrowserNotification(notification) {
        if (!('Notification' in window)) {
            return;
        }

        if (Notification.permission === 'granted') {
            new Notification(notification.data?.title || 'Notifikasi', {
                body: notification.data?.message || '',
                icon: '/assets/images/logo.png',
                tag: notification.id
            });
        } else if (Notification.permission !== 'denied') {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                new Notification(notification.data?.title || 'Notifikasi', {
                    body: notification.data?.message || '',
                    icon: '/assets/images/logo.png',
                    tag: notification.id
                });
            }
        }
    }

    /**
     * Play notification sound
     */
    playNotificationSound() {
        try {
            const audio = new Audio('/assets/sounds/notification.mp3');
            audio.volume = 0.5;
            audio.play().catch(() => {
                // Ignore errors if audio can't play
            });
        } catch (e) {
            // Ignore errors
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        const container = document.getElementById('notificationList');
        if (container) {
            container.innerHTML = `
                <div class="notification-empty">
                    <div class="notification-empty-icon">
                        <i class="ti ti-alert-circle"></i>
                    </div>
                    <p class="mb-1 text-danger">${this.escapeHtml(message)}</p>
                </div>
            `;
        }
    }

    /**
     * Format time ago
     */
    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) {
            return 'Baru saja';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} menit yang lalu`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} jam yang lalu`;
        } else if (diffInSeconds < 604800) {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} hari yang lalu`;
        } else {
            return date.toLocaleDateString('id-ID');
        }
    }

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Get CSRF token
     */
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }
}

// Initialize notification system when DOM is ready
let notificationSystem;

document.addEventListener('DOMContentLoaded', function() {
    notificationSystem = new NotificationSystem();

    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
});

// Make notificationSystem globally accessible
window.notificationSystem = notificationSystem;
