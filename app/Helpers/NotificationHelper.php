<?php

use App\Enums\NotificationType;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

/**
 * Notification Helper Functions
 * Provides convenient functions for creating notifications throughout the application
 */

if (!function_exists('create_notification')) {
    /**
     * Create a notification for the current user
     */
    function create_notification(
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $user = Auth::user();

        if ($user) {
            $service = app(NotificationService::class);
            $service->create($user, $type, $data, $url, $sourceType, $sourceId);
        }
    }
}

if (!function_exists('notify_user')) {
    /**
     * Create a notification for a specific user
     */
    function notify_user(
        $user,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        if ($user) {
            $service = app(NotificationService::class);
            $service->create($user, $type, $data, $url, $sourceType, $sourceId);
        }
    }
}

if (!function_exists('notify_all_except')) {
    /**
     * Create a notification for all users except the current user
     */
    function notify_all_except(
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $user = Auth::user();

        if ($user) {
            $service = app(NotificationService::class);
            $service->createForAllExcept($user, $type, $data, $url, $sourceType, $sourceId);
        }
    }
}

if (!function_exists('notify_role')) {
    /**
     * Create a notification for all users with a specific role
     */
    function notify_role(
        string $role,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $service = app(NotificationService::class);
        $service->createForRole($role, $type, $data, $url, $sourceType, $sourceId);
    }
}

if (!function_exists('get_unread_count')) {
    /**
     * Get the unread notification count for the current user
     */
    function get_unread_count(): int
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        $service = app(NotificationService::class);
        return $service->getUnreadCount($user);
    }
}

if (!function_exists('get_recent_notifications')) {
    /**
     * Get recent notifications for the current user
     */
    function get_recent_notifications(int $limit = 10)
    {
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        $service = app(NotificationService::class);
        return $service->getRecent($user, $limit);
    }
}

if (!function_exists('mark_all_notifications_read')) {
    /**
     * Mark all notifications as read for the current user
     */
    function mark_all_notifications_read(): int
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        $service = app(NotificationService::class);
        return $service->markAllAsRead($user);
    }
}

if (!function_exists('log_activity')) {
    /**
     * Log an activity to the activity log
     */
    function log_activity(
        string $action,
        string $modelType,
        ?int $modelId = null,
        array $properties = []
    ): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $service = app(NotificationService::class);
        $service->logActivity(
            $user,
            $action,
            $modelType,
            $modelId,
            $properties
        );
    }
}

if (!function_exists('notify_data_created')) {
    /**
     * Notify about created data
     */
    function notify_data_created(
        string $modelName,
        string $recordTitle,
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        create_notification(
            NotificationType::DATA_CREATED,
            [
                'message' => "Data {$modelName} baru telah ditambahkan: {$recordTitle}",
                'additional_data' => [
                    'model' => $modelName,
                    'title' => $recordTitle,
                ],
            ],
            $url,
            $sourceType,
            $sourceId
        );
    }
}

if (!function_exists('notify_data_updated')) {
    /**
     * Notify about updated data
     */
    function notify_data_updated(
        string $modelName,
        string $recordTitle,
        array $changes = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        create_notification(
            NotificationType::DATA_UPDATED,
            [
                'message' => "Data {$modelName} telah diperbarui: {$recordTitle}",
                'additional_data' => [
                    'model' => $modelName,
                    'title' => $recordTitle,
                    'changes' => $changes,
                ],
            ],
            $url,
            $sourceType,
            $sourceId
        );
    }
}

if (!function_exists('notify_data_deleted')) {
    /**
     * Notify about deleted data
     */
    function notify_data_deleted(
        string $modelName,
        string $recordTitle,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        create_notification(
            NotificationType::DATA_DELETED,
            [
                'message' => "Data {$modelName} telah dihapus: {$recordTitle}",
                'additional_data' => [
                    'model' => $modelName,
                    'title' => $recordTitle,
                ],
            ],
            null,
            $sourceType,
            $sourceId
        );
    }
}

if (!function_exists('notify_project')) {
    /**
     * Notify about project-related activities
     */
    function notify_project(
        string $event,
        string $projectName,
        ?string $url = null
    ): void
    {
        $type = match ($event) {
            'created' => NotificationType::PROJECT_CREATED,
            'updated' => NotificationType::PROJECT_UPDATED,
            'deleted' => NotificationType::PROJECT_DELETED,
            default => NotificationType::DATA_UPDATED,
        };

        $message = match ($event) {
            'created' => "Proyek baru telah ditambahkan: {$projectName}",
            'updated' => "Proyek telah diperbarui: {$projectName}",
            'deleted' => "Proyek telah dihapus: {$projectName}",
            default => "Proyek {$projectName} telah diproses",
        };

        create_notification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'project' => $projectName,
                    'event' => $event,
                ],
            ],
            $url,
            'Project'
        );
    }
}

if (!function_exists('notify_Ikm')) {
    /**
     * Notify about IKM-related activities
     */
    function notify_ikm(
        string $event,
        string $ikmName,
        ?string $url = null
    ): void
    {
        $type = match ($event) {
            'created' => NotificationType::Ikm_CREATED,
            'updated' => NotificationType::Ikm_UPDATED,
            'deleted' => NotificationType::Ikm_DELETED,
            'approved' => NotificationType::Ikm_APPROVED,
            'rejected' => NotificationType::Ikm_REJECTED,
            'verified' => NotificationType::Ikm_VERIFIED,
            default => NotificationType::DATA_UPDATED,
        };

        $message = match ($event) {
            'created' => "Data IKM baru telah ditambahkan: {$ikmName}",
            'updated' => "Data IKM telah diperbarui: {$ikmName}",
            'deleted' => "Data IKM telah dihapus: {$ikmName}",
            'approved' => "Data IKM {$ikmName} telah disetujui",
            'rejected' => "Data IKM {$ikmName} telah ditolak",
            'verified' => "Data IKM {$ikmName} telah diverifikasi",
            default => "Data IKM {$ikmName} telah diproses",
        };

        create_notification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'Ikm' => $ikmName,
                    'event' => $event,
                ],
            ],
            $url,
            'Ikm'
        );
    }
}

if (!function_exists('notify_Cots')) {
    /**
     * Notify about COTS-related activities
     */
    function notify_cots(
        string $event,
        string $cotsName,
        ?string $url = null
    ): void
    {
        $type = match ($event) {
            'created' => NotificationType::Cots_CREATED,
            'updated' => NotificationType::Cots_UPDATED,
            'deleted' => NotificationType::Cots_DELETED,
            'installed' => NotificationType::Cots_INSTALLED,
            'removed' => NotificationType::Cots_REMOVED,
            default => NotificationType::DATA_UPDATED,
        };

        $message = match ($event) {
            'created' => "Data COTS baru telah ditambahkan: {$cotsName}",
            'updated' => "Data COTS telah diperbarui: {$cotsName}",
            'deleted' => "Data COTS telah dihapus: {$cotsName}",
            'installed' => "COTS {$cotsName} telah diinstal",
            'removed' => "COTS {$cotsName} telah dihapus",
            default => "COTS {$cotsName} telah diproses",
        };

        create_notification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'Cots' => $cotsName,
                    'event' => $event,
                ],
            ],
            $url,
            'Cots'
        );
    }
}

if (!function_exists('notify_import_export')) {
    /**
     * Notify about import/export activities
     */
    function notify_import_export(
        string $action,
        string $type,
        int $count,
        ?string $url = null
    ): void
    {
        $notificationType = match ($action) {
            'import_completed' => NotificationType::IMPORT_COMPLETED,
            'import_failed' => NotificationType::IMPORT_FAILED,
            'export_completed' => NotificationType::EXPORT_COMPLETED,
            'export_failed' => NotificationType::EXPORT_FAILED,
            default => NotificationType::DATA_IMPORTED,
        };

        $message = match ($action) {
            'import_completed' => "Import {$type} selesai. {$count} data berhasil di-import.",
            'import_failed' => "Import {$type} gagal. Silakan periksa format file.",
            'export_completed' => "Export {$type} selesai. {$count} data berhasil di-export.",
            'export_failed' => "Export {$type} gagal. Silakan coba lagi.",
            default => "Proses {$action} telah selesai.",
        };

        create_notification(
            $notificationType,
            [
                'message' => $message,
                'additional_data' => [
                    'type' => $type,
                    'count' => $count,
                ],
            ],
            $url
        );
    }
}

if (!function_exists('notify_security')) {
    /**
     * Notify about security events
     */
    function notify_security(string $event, string $details): void
    {
        $type = match ($event) {
            'alert' => NotificationType::SECURITY_ALERT,
            'unauthorized_access' => NotificationType::UNAUTHORIZED_ACCESS,
            'suspicious_activity' => NotificationType::SUSPICIOUS_ACTIVITY,
            default => NotificationType::SYSTEM_WARNING,
        };

        $message = match ($event) {
            'alert' => "Peringatan keamanan: {$details}",
            'unauthorized_access' => "Percobaan akses tidak sah: {$details}",
            'suspicious_activity' => "Aktivitas mencurigakan terdeteksi: {$details}",
            default => "Notifikasi keamanan: {$details}",
        };

        create_notification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'event' => $event,
                    'details' => $details,
                ],
            ]
        );
    }
}

if (!function_exists('format_notification_time')) {
    /**
     * Format notification time for display
     */
    function format_notification_time($dateTime): string
    {
        if (!$dateTime) {
            return 'Tidak diketahui';
        }

        $date = $dateTime instanceof \Carbon\Carbon ? $dateTime : \Carbon\Carbon::parse($dateTime);
        $now = \Carbon\Carbon::now();
        $diffInSeconds = $now->diffInSeconds($date);

        if ($diffInSeconds < 60) {
            return 'Baru saja';
        } elseif ($diffInSeconds < 3600) {
            $minutes = floor($diffInSeconds / 60);
            return "{$minutes} menit yang lalu";
        } elseif ($diffInSeconds < 86400) {
            $hours = floor($diffInSeconds / 3600);
            return "{$hours} jam yang lalu";
        } elseif ($diffInSeconds < 604800) {
            $days = floor($diffInSeconds / 86400);
            return "{$days} hari yang lalu";
        } else {
            return $date->isoFormat('D MMMM Y, HH:mm');
        }
    }
}

if (!function_exists('get_notification_icon')) {
    /**
     * Get icon for notification type
     */
    function get_notification_icon(string $type): string
    {
        $notificationType = NotificationType::tryFrom($type);
        return $notificationType ? $notificationType->getIcon() : 'ti ti-bell';
    }
}

if (!function_exists('get_notification_color')) {
    /**
     * Get color for notification type
     */
    function get_notification_color(string $type): string
    {
        $notificationType = NotificationType::tryFrom($type);
        return $notificationType ? $notificationType->getColor() : 'secondary';
    }
}

if (!function_exists('get_notification_title')) {
    /**
     * Get title for notification type
     */
    function get_notification_title(string $type): string
    {
        $notificationType = NotificationType::tryFrom($type);
        return $notificationType ? $notificationType->getTitle() : 'Notifikasi';
    }
}

if (!function_exists('get_notification_category')) {
    /**
     * Get category for notification type
     */
    function get_notification_category(string $type): string
    {
        $notificationType = NotificationType::tryFrom($type);
        return $notificationType ? $notificationType->getCategory() : 'general';
    }
}
