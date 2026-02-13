{{--
    Comprehensive Notification Dropdown
    Displays real-time notifications with icons, timestamps, and status indicators
--}}
@php
use App\Enums\NotificationType;

// Get current user's notifications using orderBy directly
$recentNotifications = auth()->user()
    ->notifications()
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
$unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<style>
    /* Notification Dropdown Styles */
    .notification-dropdown {
        min-width: 380px;
        max-width: 420px;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #e9ecef;
    }

    .notification-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f3f5;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item.unread {
        background-color: #e7f1ff;
        border-left: 3px solid #435ebe;
    }

    .notification-item.unread:hover {
        background-color: #dbe4ff;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notification-icon.success {
        background-color: #d1e7dd;
        color: #198754;
    }

    .notification-icon.danger {
        background-color: #f8d7da;
        color: #dc3545;
    }

    .notification-icon.warning {
        background-color: #fff3cd;
        color: #ffc107;
    }

    .notification-icon.info {
        background-color: #cff4fc;
        color: #0dcaf0;
    }

    .notification-icon.primary {
        background-color: #435ebe;
        color: #fff;
    }

    .notification-icon.secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .notification-content {
        flex-grow: 1;
        min-width: 0;
    }

    .notification-title {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 2px;
    }

    .notification-message {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: #adb5bd;
    }

    .notification-time {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notification-status {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 500;
    }

    .notification-status.success {
        background-color: #d1e7dd;
        color: #198754;
    }

    .notification-status.failure {
        background-color: #f8d7da;
        color: #dc3545;
    }

    .notification-status.info {
        background-color: #cff4fc;
        color: #0dcaf0;
    }

    .notification-actions {
        display: flex;
        gap: 8px;
    }

    .notification-btn {
        background: none;
        border: none;
        padding: 4px;
        cursor: pointer;
        color: #adb5bd;
        transition: color 0.2s;
    }

    .notification-btn:hover {
        color: #495057;
    }

    .notification-btn.read-btn:hover {
        color: #435ebe;
    }

    .notification-btn.delete-btn:hover {
        color: #dc3545;
    }

    .notification-empty {
        padding: 40px 20px;
        text-align: center;
        color: #adb5bd;
    }

    .notification-empty-icon {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    .notification-footer {
        padding: 12px 16px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-filter-tabs {
        display: flex;
        gap: 8px;
        padding: 8px 16px;
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    .notification-filter-tab {
        padding: 6px 12px;
        border: none;
        background: none;
        font-size: 12px;
        color: #6c757d;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .notification-filter-tab:hover {
        background-color: #e9ecef;
    }

    .notification-filter-tab.active {
        background-color: #435ebe;
        color: #fff;
    }

    .mark-all-read-btn {
        font-size: 12px;
        color: #435ebe;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .mark-all-read-btn:hover {
        background-color: #e7f1ff;
    }

    .unread-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #dc3545;
        color: #fff;
        font-size: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }

    .notification-loading {
        padding: 20px;
        text-align: center;
        color: #adb5bd;
    }

    .loading-spinner {
        width: 24px;
        height: 24px;
        border: 2px solid #e9ecef;
        border-top-color: #435ebe;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 8px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Dark mode support */
    [data-theme="dark"] .notification-item {
        border-color: #3d4655;
    }

    [data-theme="dark"] .notification-item:hover {
        background-color: #2c3444;
    }

    [data-theme="dark"] .notification-item.unread {
        background-color: #1e3a5f;
        border-left-color: #5c7cfa;
    }

    [data-theme="dark"] .notification-item.unread:hover {
        background-color: #284a7a;
    }

    [data-theme="dark"] .notification-title {
        color: #f8f9fa;
    }

    [data-theme="dark"] .notification-message {
        color: #adb5bd;
    }

    [data-theme="dark"] .notification-meta {
        color: #6c757d;
    }

    [data-theme="dark"] .notification-header,
    [data-theme="dark"] .notification-footer,
    [data-theme="dark"] .notification-filter-tabs {
        border-color: #3d4655;
    }

    [data-theme="dark"] .notification-filter-tabs {
        background-color: #2c3444;
    }

    [data-theme="dark"] .notification-filter-tab:hover {
        background-color: #3d4655;
    }

    [data-theme="dark"] .mark-all-read-btn:hover {
        background-color: #1e3a5f;
    }

    [data-theme="dark"] .notification-empty {
        color: #6c757d;
    }
    @media (max-width: 991px) {
        .notificationToggle {
            display: none !important;
        }
}
</style>

<div class="dropdown topbar-item notificationToggle">
    {{-- Notification Toggle Button --}}
    <button class="topbar-link dropdown-toggle drop-arrow-none position-relative"
            data-bs-toggle="dropdown"
            data-bs-auto-close="outside"
            aria-haspopup="false"
            aria-expanded="false"
            id="notificationToggle">
        <i class="ti ti-bell topbar-link-icon"></i>
        @if($unreadCount > 0)
            <span class="badge text-bg-danger badge-circle topbar-badge" id="unreadBadge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>

    {{-- Notification Dropdown Menu --}}
    <div class="dropdown-menu p-0 dropdown-menu-end">
        {{-- Header --}}
        <div class="notification-header">
            <div>
                <h6 class="m-0 fs-md fw-semibold">Notifikasi</h6>
                <span class="fs-xs text-muted">{{ $unreadCount }} belum dibaca</span>
            </div>
            <div class="d-flex gap-2">
                <button class="mark-all-read-btn" onclick="markAllAsRead()" title="Tandai semua sudah dibaca">
                    <i class="ti ti-check"></i> Tandai semua dibaca
                </button>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="notification-filter-tabs">
            <button class="notification-filter-tab active" data-filter="all" onclick="filterNotifications('all')">
                Semua
            </button>
            <button class="notification-filter-tab" data-filter="unread" onclick="filterNotifications('unread')">
                Belum Dibaca
            </button>
            <button class="notification-filter-tab" data-filter="success" onclick="filterNotifications('success')">
                Berhasil
            </button>
            <button class="notification-filter-tab" data-filter="failure" onclick="filterNotifications('failure')">
                Gagal
            </button>
        </div>

        {{-- Notification List --}}
        <div class="notification-list" id="notificationList">
            @if($recentNotifications->count() > 0)
                @foreach($recentNotifications as $notification)
                    @php
                        $type = NotificationType::tryFrom($notification->type);
                        $color = $type ? $type->getColor() : 'secondary';
                        $title = $type ? $type->getTitle() : 'Notifikasi';
                        $icon = $type ? $type->getIcon() : 'ti ti-bell';
                        $status = $notification->data['status'] ?? 'info';
                        $message = $notification->data['message'] ?? '';
                        $isUnread = is_null($notification->read_at);
                    @endphp

                    <div class="notification-item {{ $isUnread ? 'unread' : '' }}"
                         data-notification-id="{{ $notification->id }}"
                         data-status="{{ $status }}"
                         data-is-unread="{{ $isUnread ? 'true' : 'false' }}"
                         onclick="handleNotificationClick('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">

                        {{-- Icon --}}
                        <div class="notification-icon {{ $color }}">
                            <i class="{{ $icon }}"></i>
                        </div>

                        {{-- Content --}}
                        <div class="notification-content">
                            <div class="notification-title">{{ $title }}</div>
                            <div class="notification-message">{{ $message }}</div>
                            <div class="notification-meta">
                                <span class="notification-time">
                                    <i class="ti ti-clock"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                <span class="notification-status {{ $status }}">
                                    @if($status === 'success')
                                        <i class="ti ti-check"></i>
                                    @elseif($status === 'failure')
                                        <i class="ti ti-x"></i>
                                    @else
                                        <i class="ti ti-info-circle"></i>
                                    @endif
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="notification-actions" onclick="event.stopPropagation()">
                            @if($isUnread)
                                <button class="notification-btn read-btn"
                                        onclick="markAsRead('{{ $notification->id }}')"
                                        title="Tandai sudah dibaca">
                                    <i class="ti ti-check"></i>
                                </button>
                            @else
                                <button class="notification-btn"
                                        onclick="markAsUnread('{{ $notification->id }}')"
                                        title="Tandai belum dibaca">
                                    <i class="ti ti-circle"></i>
                                </button>
                            @endif
                            <button class="notification-btn delete-btn"
                                    onclick="deleteNotification('{{ $notification->id }}')"
                                    title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="notification-empty">
                    <div class="notification-empty-icon">
                        <i class="ti ti-bell-off"></i>
                    </div>
                    <p class="mb-1">Tidak ada notifikasi</p>
                    <span class="fs-xs">Notifikasi baru akan muncul di sini</span>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="notification-footer">
            <a href="{{ route('notifications.settings') }}" class="text-reset text-decoration-underline link-offset-2 fw-bold fs-sm">
                Pengaturan Notifikasi
            </a>
            <a href="#" onclick="viewAllNotifications(event)" class="text-reset text-decoration-underline link-offset-2 fw-bold fs-sm">
                Lihat Semua
            </a>
        </div>
    </div>
</div>

{{-- JavaScript for Notification Functionality --}}
<script>
    // Configuration
    const POLLING_INTERVAL = 30000; // 30 seconds
    let currentFilter = 'all';
    let lastUnreadCount = {{ $unreadCount }};
    let notificationPolling = null;

    // Initialize notifications
    document.addEventListener('DOMContentLoaded', function() {
        initializeNotifications();

        // Start polling for new notifications
        startNotificationPolling();
    });

    function initializeNotifications() {
        // Initialize filter tabs
        setupFilterTabs();

        // Load initial notifications
        loadNotifications();
    }

    function setupFilterTabs() {
        const tabs = document.querySelectorAll('.notification-filter-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                loadNotifications();
            });
        });
    }

    function loadNotifications() {
        const list = document.getElementById('notificationList');
        list.innerHTML = `
            <div class="notification-loading">
                <div class="loading-spinner"></div>
                <p>Memuat notifikasi...</p>
            </div>
        `;

        // Fetch from server
        fetch(`/notifications/recent?limit=10`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderNotifications(data.data, data.unread_count);
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                list.innerHTML = `
                    <div class="notification-empty">
                        <div class="notification-empty-icon">
                            <i class="ti ti-alert-circle"></i>
                        </div>
                        <p class="mb-1">Gagal memuat notifikasi</p>
                        <span class="fs-xs">Silakan coba lagi</span>
                    </div>
                `;
            });
    }

    function renderNotifications(notifications, unreadCount) {
        const list = document.getElementById('notificationList');
        const badge = document.getElementById('unreadBadge');

        // Update badge
        if (badge) {
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            badge.style.display = unreadCount > 0 ? 'flex' : 'none';
        }

        // Update unread count in header
        const headerCount = document.querySelector('.notification-header .fs-xs');
        if (headerCount) {
            headerCount.textContent = `${unreadCount} belum dibaca`;
        }

        if (!notifications || notifications.length === 0) {
            list.innerHTML = `
                <div class="notification-empty">
                    <div class="notification-empty-icon">
                        <i class="ti ti-bell-off"></i>
                    </div>
                    <p class="mb-1">Tidak ada notifikasi</p>
                    <span class="fs-xs">Notifikasi baru akan muncul di sini</span>
                </div>
            `;
            return;
        }

        // Filter notifications based on current filter
        let filteredNotifications = notifications;
        if (currentFilter === 'unread') {
            filteredNotifications = notifications.filter(n => n.read_at === null);
        } else if (currentFilter === 'success') {
            filteredNotifications = notifications.filter(n => n.data && n.data.status === 'success');
        } else if (currentFilter === 'failure') {
            filteredNotifications = notifications.filter(n => n.data && n.data.status === 'failure');
        }

        if (filteredNotifications.length === 0) {
            list.innerHTML = `
                <div class="notification-empty">
                    <div class="notification-empty-icon">
                        <i class="ti ti-filter"></i>
                    </div>
                    <p class="mb-1">Tidak ada notifikasi yang cocok</p>
                    <span class="fs-xs">Coba filter lain</span>
                </div>
            `;
            return;
        }

        // Render notifications
        let html = '';
        filteredNotifications.forEach(notification => {
            const type = notification.type;
            const icon = notification.data.icon || 'ti ti-bell';
            const color = notification.data.color || 'secondary';
            const title = notification.data.title || 'Notifikasi';
            const message = notification.data.message || '';
            const status = notification.data.status || 'info';
            const isUnread = notification.read_at === null;
            const timeAgo = formatTimeAgo(new Date(notification.created_at));
            const url = notification.data.url || '#';

            html += `
                <div class="notification-item ${isUnread ? 'unread' : ''}"
                     data-notification-id="${notification.id}"
                     data-status="${status}"
                     data-is-unread="${isUnread}"
                     onclick="handleNotificationClick('${notification.id}', '${url}')">

                    <div class="notification-icon ${color}">
                        <i class="${icon}"></i>
                    </div>

                    <div class="notification-content">
                        <div class="notification-title">${title}</div>
                        <div class="notification-message">${message}</div>
                        <div class="notification-meta">
                            <span class="notification-time">
                                <i class="ti ti-clock"></i>
                                ${timeAgo}
                            </span>
                            <span class="notification-status ${status}">
                                ${getStatusIcon(status)}
                                ${capitalize(status)}
                            </span>
                        </div>
                    </div>

                    <div class="notification-actions" onclick="event.stopPropagation()">
                        ${isUnread
                            ? `<button class="notification-btn read-btn" onclick="markAsRead('${notification.id}')" title="Tandai sudah dibaca">
                                <i class="ti ti-check"></i>
                               </button>`
                            : `<button class="notification-btn" onclick="markAsUnread('${notification.id}')" title="Tandai belum dibaca">
                                <i class="ti ti-circle"></i>
                               </button>`
                        }
                        <button class="notification-btn delete-btn" onclick="deleteNotification('${notification.id}')" title="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        list.innerHTML = html;
    }

    function getStatusIcon(status) {
        switch(status) {
            case 'success': return '<i class="ti ti-check"></i>';
            case 'failure': return '<i class="ti ti-x"></i>';
            default: return '<i class="ti ti-info-circle"></i>';
        }
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function formatTimeAgo(date) {
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) {
            return 'Baru saja';
        } else if (seconds < 3600) {
            const minutes = Math.floor(seconds / 60);
            return `${minutes} menit lalu`;
        } else if (seconds < 86400) {
            const hours = Math.floor(seconds / 3600);
            return `${hours} jam lalu`;
        } else if (seconds < 604800) {
            const days = Math.floor(seconds / 86400);
            return `${days} hari lalu`;
        } else {
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        }
    }

    function handleNotificationClick(notificationId, url) {
        // Mark as read
        markAsRead(notificationId);

        // Navigate to URL if provided
        if (url && url !== '#') {
            window.location.href = url;
        }
    }

    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update badge
                const badge = document.getElementById('unreadBadge');
                if (badge && data.unread_count !== undefined) {
                    badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    badge.style.display = data.unread_count > 0 ? 'flex' : 'none';
                }

                // Reload notifications
                loadNotifications();
            }
        })
        .catch(error => console.error('Error marking as read:', error));
    }

    function markAsUnread(notificationId) {
        fetch(`/notifications/${notificationId}/unread`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        })
        .catch(error => console.error('Error marking as unread:', error));
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();

                // Show success toast
                showToast('Semua notifikasi ditandai sudah dibaca', 'success');
            }
        })
        .catch(error => console.error('Error marking all as read:', error));
    }

    function deleteNotification(notificationId) {
        if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
            return;
        }

        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                showToast('Notifikasi dihapus', 'success');
            }
        })
        .catch(error => console.error('Error deleting notification:', error));
    }

    function filterNotifications(filter) {
        currentFilter = filter;
        loadNotifications();
    }

    function viewAllNotifications(event) {
        event.preventDefault();
        // Open full notification page or scroll to notification section

    }

    function startNotificationPolling() {
        // Clear existing polling
        if (notificationPolling) {
            clearInterval(notificationPolling);
        }

        // Start new polling
        notificationPolling = setInterval(() => {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.unread_count !== lastUnreadCount) {
                        lastUnreadCount = data.unread_count;

                        // Update badge
                        const badge = document.getElementById('unreadBadge');
                        if (badge) {
                            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                            badge.style.display = data.unread_count > 0 ? 'flex' : 'none';
                        }

                        // Reload notifications if dropdown is open
                        const dropdown = document.querySelector('.notification-dropdown');
                        if (dropdown && dropdown.classList.contains('show')) {
                            loadNotifications();
                        }
                    }
                })
                .catch(error => console.error('Error polling notifications:', error));
        }, POLLING_INTERVAL);
    }

    function showToast(message, type = 'info') {
        // Use SweetAlert2 for notifications
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (notificationPolling) {
            clearInterval(notificationPolling);
        }
    });
</script>
