<?php

namespace App\Traits;

use App\Enums\NotificationType;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

trait CreatesNotifications
{
    protected NotificationService $notificationService;

    /**
     * Initialize the notification service.
     */
    public function initializeNotificationService(): void
    {
        $this->notificationService = app(NotificationService::class);
    }

    /**
     * Get the notification service instance.
     */
    protected function notificationService(): NotificationService
    {
        if (!isset($this->notificationService)) {
            $this->initializeNotificationService();
        }

        return $this->notificationService;
    }

    /**
     * Create a notification for the current user.
     */
    protected function createNotification(
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $user = Auth::user();

        if ($user) {
            $this->notificationService()->create($user, $type, $data, $url, $sourceType, $sourceId);
        }
    }

    /**
     * Create a notification for a specific user.
     */
    protected function notifyUser(
        $user,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        if ($user) {
            $this->notificationService()->create($user, $type, $data, $url, $sourceType, $sourceId);
        }
    }

    /**
     * Create notifications for multiple users.
     */
    protected function notifyUsers(
        array $users,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $this->notificationService()->createForUsers($users, $type, $data, $url, $sourceType, $sourceId);
    }

    /**
     * Create notifications for all users except the current user.
     */
    protected function notifyAllExceptCurrent(
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $user = Auth::user();

        if ($user) {
            $this->notificationService()->createForAllExcept($user, $type, $data, $url, $sourceType, $sourceId);
        }
    }

    // ==================== AUTHENTICATION NOTIFICATIONS ====================

    /**
     * Create notification for successful login.
     */
    protected function notifyLogin(bool $success = true): void
    {
        $type = $success ? NotificationType::LOGIN : NotificationType::LOGIN_FAILED;

        $this->createNotification(
            $type,
            [
                'message' => $success
                    ? 'Anda telah berhasil login ke sistem'
                    : 'Percobaan login gagal. Silakan periksa kredensial Anda.',
            ]
        );
    }

    /**
     * Create notification for logout.
     */
    protected function notifyLogout(): void
    {
        $this->createNotification(
            NotificationType::LOGOUT,
            [
                'message' => 'Anda telah logout dari sistem',
            ]
        );
    }

    /**
     * Create notification for password reset.
     */
    protected function notifyPasswordReset(string $email): void
    {
        $this->createNotification(
            NotificationType::PASSWORD_RESET,
            [
                'message' => 'Link reset password telah dikirim ke email Anda',
                'additional_data' => [
                    'email' => $email,
                ],
            ]
        );
    }

    /**
     * Create notification for password changed.
     */
    protected function notifyPasswordChanged(): void
    {
        $this->createNotification(
            NotificationType::PASSWORD_CHANGED,
            [
                'message' => 'Password Anda telah berhasil diubah',
            ]
        );
    }

    // ==================== DATA OPERATION NOTIFICATIONS ====================

    /**
     * Create notification for data creation.
     */
    protected function notifyDataCreated(
        string $modelName,
        string $recordTitle,
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $this->createNotification(
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

    /**
     * Create notification for data update.
     */
    protected function notifyDataUpdated(
        string $modelName,
        string $recordTitle,
        array $changes = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $this->createNotification(
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

    /**
     * Create notification for data deletion.
     */
    protected function notifyDataDeleted(
        string $modelName,
        string $recordTitle,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $this->createNotification(
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

    /**
     * Create notification for data restoration.
     */
    protected function notifyDataRestored(
        string $modelName,
        string $recordTitle,
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $this->createNotification(
            NotificationType::DATA_RESTORED,
            [
                'message' => "Data {$modelName} telah dipulihkan: {$recordTitle}",
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

    /**
     * Create notification for data import.
     */
    protected function notifyDataImported(string $modelName, int $count, ?string $url = null): void
    {
        $this->createNotification(
            NotificationType::DATA_IMPORTED,
            [
                'message' => "Import {$modelName} selesai. {$count} data berhasil di-import.",
                'additional_data' => [
                    'model' => $modelName,
                    'count' => $count,
                ],
            ],
            $url
        );
    }

    /**
     * Create notification for data export.
     */
    protected function notifyDataExported(string $modelName, int $count, ?string $url = null): void
    {
        $this->createNotification(
            NotificationType::DATA_EXPORTED,
            [
                'message' => "Export {$modelName} selesai. {$count} data berhasil di-export.",
                'additional_data' => [
                    'model' => $modelName,
                    'count' => $count,
                ],
            ],
            $url
        );
    }

    // ==================== FORM NOTIFICATIONS ====================

    /**
     * Create notification for form submission.
     */
    protected function notifyFormSubmitted(string $formName, ?string $url = null): void
    {
        $this->createNotification(
            NotificationType::FORM_SUBMITTED,
            [
                'message' => "Form {$formName} telah dikirim",
            ],
            $url
        );
    }

    /**
     * Create notification for form approval.
     */
    protected function notifyFormApproved(string $formName, string $title, ?string $url = null): void
    {
        $this->createNotification(
            NotificationType::FORM_APPROVED,
            [
                'message' => "{$formName} \"{$title}\" telah disetujui",
                'additional_data' => [
                    'form' => $formName,
                    'title' => $title,
                ],
            ],
            $url
        );
    }

    /**
     * Create notification for form rejection.
     */
    protected function notifyFormRejected(string $formName, string $title, ?string $reason = null, ?string $url = null): void
    {
        $this->createNotification(
            NotificationType::FORM_REJECTED,
            [
                'message' => "{$formName} \"{$title}\" telah ditolak",
                'additional_data' => [
                    'form' => $formName,
                    'title' => $title,
                    'reason' => $reason,
                ],
            ],
            $url
        );
    }

    // ==================== PROFILE NOTIFICATIONS ====================

    /**
     * Create notification for profile update.
     */
    protected function notifyProfileUpdated(array $fields = []): void
    {
        $this->createNotification(
            NotificationType::PROFILE_UPDATED,
            [
                'message' => 'Profil Anda telah diperbarui',
                'additional_data' => [
                    'updated_fields' => $fields,
                ],
            ],
            '/profile'
        );
    }

    /**
     * Create notification for profile photo update.
     */
    protected function notifyProfilePhotoUpdated(): void
    {
        $this->createNotification(
            NotificationType::PROFILE_PHOTO_UPDATED,
            [
                'message' => 'Foto profil Anda telah diubah',
            ],
            '/profile'
        );
    }

    // ==================== PROJECT/IKM/COTS NOTIFICATIONS ====================

    /**
     * Create notification for project/IKM/COTS specific events.
     */
    protected function notifyContentEvent(
        string $event,
        string $contentType,
        string $title,
        ?string $url = null
    ): void
    {
        $type = match ($event) {
            'created' => match ($contentType) {
                'project' => NotificationType::PROJECT_CREATED,
                'ikm' => NotificationType::IKM_CREATED,
                'cots' => NotificationType::COTS_CREATED,
                'benchmark' => NotificationType::BENCHMARK_CREATED,
                'dokumentasi' => NotificationType::DOKUMENTASI_CREATED,
                'produk_design' => NotificationType::PRODUK_DESIGN_CREATED,
                default => NotificationType::DATA_CREATED,
            },
            'updated' => match ($contentType) {
                'project' => NotificationType::PROJECT_UPDATED,
                'ikm' => NotificationType::IKM_UPDATED,
                'cots' => NotificationType::COTS_UPDATED,
                'benchmark' => NotificationType::BENCHMARK_UPDATED,
                'dokumentasi' => NotificationType::DOKUMENTASI_UPDATED,
                'produk_design' => NotificationType::PRODUK_DESIGN_UPDATED,
                default => NotificationType::DATA_UPDATED,
            },
            'deleted' => match ($contentType) {
                'project' => NotificationType::PROJECT_DELETED,
                'ikm' => NotificationType::IKM_DELETED,
                'cots' => NotificationType::COTS_DELETED,
                'benchmark' => NotificationType::BENCHMARK_DELETED,
                'dokumentasi' => NotificationType::DOKUMENTASI_DELETED,
                'produk_design' => NotificationType::PRODUK_DESIGN_DELETED,
                default => NotificationType::DATA_DELETED,
            },
            'approved' => match ($contentType) {
                'ikm' => NotificationType::IKM_APPROVED,
                'benchmark' => NotificationType::BENCHMARK_APPROVED,
                'dokumentasi' => NotificationType::DOKUMENTASI_APPROVED,
                'produk_design' => NotificationType::PRODUK_DESIGN_APPROVED,
                default => NotificationType::FORM_APPROVED,
            },
            'rejected' => match ($contentType) {
                'ikm' => NotificationType::IKM_REJECTED,
                'benchmark' => NotificationType::BENCHMARK_REJECTED,
                'produk_design' => NotificationType::PRODUK_DESIGN_REJECTED,
                default => NotificationType::FORM_REJECTED,
            },
            default => NotificationType::DATA_CREATED,
        };

        $verb = match ($event) {
            'created' => 'baru telah ditambahkan',
            'updated' => 'telah diperbarui',
            'deleted' => 'telah dihapus',
            'approved' => 'telah disetujui',
            'rejected' => 'telah ditolak',
            default => 'telah diproses',
        };

        $this->createNotification(
            $type,
            [
                'message' => "{$this->getContentTypeLabel($contentType)} {$title} {$verb}",
                'additional_data' => [
                    'content_type' => $contentType,
                    'title' => $title,
                    'event' => $event,
                ],
            ],
            $url,
            $contentType
        );
    }

    /**
     * Get content type label.
     */
    protected function getContentTypeLabel(string $contentType): string
    {
        return match ($contentType) {
            'project' => 'Proyek',
            'ikm' => 'Data IKM',
            'cots' => 'Data COTS',
            'benchmark' => 'Benchmark Produk',
            'dokumentasi' => 'Dokumentasi COTS',
            'produk_design' => 'Desain Produk',
            default => ucfirst($contentType),
        };
    }

    // ==================== TRANSACTION NOTIFICATIONS ====================

    /**
     * Create notification for transaction.
     */
    protected function notifyTransaction(string $action, string $description, ?string $url = null): void
    {
        $type = match ($action) {
            'created' => NotificationType::TRANSACTION_CREATED,
            'completed' => NotificationType::TRANSACTION_COMPLETED,
            'failed' => NotificationType::TRANSACTION_FAILED,
            'cancelled' => NotificationType::TRANSACTION_CANCELLED,
            'refunded' => NotificationType::TRANSACTION_REFUNDED,
            default => NotificationType::TRANSACTION_CREATED,
        };

        $this->createNotification(
            $type,
            [
                'message' => $description,
            ],
            $url
        );
    }

    // ==================== EXPORT/IMPORT NOTIFICATIONS ====================

    /**
     * Create notification for export/import events.
     */
    protected function notifyExportImport(string $action, string $type, int $count, ?string $url = null): void
    {
        $notificationType = match ($action) {
            'export_started' => NotificationType::EXPORT_STARTED,
            'export_completed' => NotificationType::EXPORT_COMPLETED,
            'export_failed' => NotificationType::EXPORT_FAILED,
            'import_started' => NotificationType::IMPORT_STARTED,
            'import_completed' => NotificationType::IMPORT_COMPLETED,
            'import_failed' => NotificationType::IMPORT_FAILED,
            default => NotificationType::EXPORT_COMPLETED,
        };

        $message = match ($action) {
            'export_started' => "Export {$type} sedang dimulai...",
            'export_completed' => "Export {$type} selesai. {$count} data berhasil di-export.",
            'export_failed' => "Export {$type} gagal. Silakan periksa format file dan coba lagi.",
            'import_started' => "Import {$type} sedang dimulai...",
            'import_completed' => "Import {$type} selesai. {$count} data berhasil di-import.",
            'import_failed' => "Import {$type} gagal. Silakan periksa format file dan coba lagi.",
            default => "Proses {$action} telah selesai.",
        };

        $this->createNotification(
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

    // ==================== SYSTEM NOTIFICATIONS ====================

    /**
     * Create notification for system events.
     */
    protected function notifySystem(string $level, string $message, ?string $url = null): void
    {
        $type = match ($level) {
            'error' => NotificationType::SYSTEM_ERROR,
            'warning' => NotificationType::SYSTEM_WARNING,
            'info' => NotificationType::SYSTEM_INFO,
            'backup_started' => NotificationType::BACKUP_STARTED,
            'backup_completed' => NotificationType::BACKUP_COMPLETED,
            'backup_failed' => NotificationType::BACKUP_FAILED,
            'maintenance_started' => NotificationType::MAINTENANCE_STARTED,
            'maintenance_completed' => NotificationType::MAINTENANCE_COMPLETED,
            default => NotificationType::SYSTEM_INFO,
        };

        $this->createNotification(
            $type,
            [
                'message' => $message,
            ],
            $url
        );
    }

    // ==================== USER ACTIVITY NOTIFICATIONS ====================

    /**
     * Create notification for user activities.
     */
    protected function notifyUserActivity(string $activity, string $username, ?string $url = null): void
    {
        $type = match ($activity) {
            'logged_in' => NotificationType::USER_LOGGED_IN,
            'logged_out' => NotificationType::USER_LOGGED_OUT,
            'created' => NotificationType::USER_CREATED,
            'updated' => NotificationType::USER_UPDATED,
            'deleted' => NotificationType::USER_DELETED,
            'role_changed' => NotificationType::USER_ROLE_CHANGED,
            default => NotificationType::USER_UPDATED,
        };

        $message = match ($activity) {
            'logged_in' => "{$username} telah login ke sistem",
            'logged_out' => "{$username} telah logout dari sistem",
            'created' => "Pengguna baru {$username} telah ditambahkan",
            'updated' => "Pengguna {$username} telah diperbarui",
            'deleted' => "Pengguna {$username} telah dihapus",
            'role_changed' => "Peran pengguna {$username} telah diubah",
            default => "Aktivitas pengguna {$username}: {$activity}",
        };

        $this->createNotification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'username' => $username,
                    'activity' => $activity,
                ],
            ],
            $url
        );
    }

    // ==================== SECURITY NOTIFICATIONS ====================

    /**
     * Create notification for security events.
     */
    protected function notifySecurity(string $event, string $details, ?string $url = null): void
    {
        $type = match ($event) {
            'alert' => NotificationType::SECURITY_ALERT,
            'unauthorized_access' => NotificationType::UNAUTHORIZED_ACCESS,
            'suspicious_activity' => NotificationType::SUSPICIOUS_ACTIVITY,
            'password_expired' => NotificationType::PASSWORD_EXPIRED,
            '2fa_enabled' => NotificationType::TWO_FACTOR_ENABLED,
            '2fa_disabled' => NotificationType::TWO_FACTOR_DISABLED,
            default => NotificationType::SECURITY_ALERT,
        };

        $message = match ($event) {
            'alert' => "Peringatan keamanan: {$details}",
            'unauthorized_access' => "Percobaan akses tidak sah: {$details}",
            'suspicious_activity' => "Aktivitas mencurigakan terdeteksi: {$details}",
            'password_expired' => "Password Anda telah kedaluwarsa. Silakan ubah password Anda.",
            '2fa_enabled' => "Autentikasi dua faktor telah diaktifkan untuk akun Anda",
            '2fa_disabled' => "Autentikasi dua faktor telah dinonaktifkan untuk akun Anda",
            default => "Notifikasi keamanan: {$details}",
        };

        $this->createNotification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'event' => $event,
                    'details' => $details,
                ],
            ],
            $url
        );
    }

    // ==================== SYNC & INTEGRATION NOTIFICATIONS ====================

    /**
     * Create notification for sync events.
     */
    protected function notifySync(string $status, string $service, ?string $url = null): void
    {
        $type = match ($status) {
            'started' => NotificationType::SYNC_STARTED,
            'completed' => NotificationType::SYNC_COMPLETED,
            'failed' => NotificationType::SYNC_FAILED,
            default => NotificationType::SYNC_STARTED,
        };

        $message = match ($status) {
            'started' => "Sinkronisasi dengan {$service} sedang dimulai...",
            'completed' => "Sinkronisasi dengan {$service} telah selesai",
            'failed' => "Sinkronisasi dengan {$service} gagal. Silakan coba lagi.",
            default => "Status sinkronisasi {$service}: {$status}",
        };

        $this->createNotification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'service' => $service,
                    'status' => $status,
                ],
            ],
            $url
        );
    }

    // ==================== REPORT NOTIFICATIONS ====================

    /**
     * Create notification for report events.
     */
    protected function notifyReport(string $status, string $reportName, ?string $url = null): void
    {
        $type = match ($status) {
            'generated' => NotificationType::REPORT_GENERATED,
            'exported' => NotificationType::REPORT_EXPORTED,
            'failed' => NotificationType::REPORT_FAILED,
            default => NotificationType::REPORT_GENERATED,
        };

        $message = match ($status) {
            'generated' => "Laporan \"{$reportName}\" telah dibuat",
            'exported' => "Laporan \"{$reportName}\" telah diexport",
            'failed' => "Gagal membuat laporan \"{$reportName}\"",
            default => "Status laporan \"{$reportName}\": {$status}",
        };

        $this->createNotification(
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'report_name' => $reportName,
                    'status' => $status,
                ],
            ],
            $url
        );
    }
}
