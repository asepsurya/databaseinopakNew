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
            $this->notificationService->create($user, $type, $data, $url, $sourceType, $sourceId);
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
            $this->notificationService->create($user, $type, $data, $url, $sourceType, $sourceId);
        }
    }

    /**
     * Create notifications for login event.
     */
    protected function notifyLogin(bool $success = true): void
    {
        $type = $success ? NotificationType::LOGIN : NotificationType::LOGIN_FAILED;

        $this->createNotification(
            $type,
            [
                'message' => $success
                    ? 'Anda telah berhasil login ke sistem'
                    : 'Percobaan login gagal',
            ]
        );
    }

    /**
     * Create notification for data creation.
     */
    protected function notifyDataCreated(string $modelName, string $recordTitle, ?string $url = null): void
    {
        $this->createNotification(
            NotificationType::DATA_CREATED,
            [
                'message' => "Data {$modelName} baru telah ditambahkan: {$recordTitle}",
                'additional_data' => [
                    'model' => $modelName,
                    'title' => $recordTitle,
                ]
            ],
            $url,
            $modelName
        );
    }

    /**
     * Create notification for data update.
     */
    protected function notifyDataUpdated(string $modelName, string $recordTitle, ?string $url = null): void
    {
        $this->createNotification(
            NotificationType::DATA_UPDATED,
            [
                'message' => "Data {$modelName} telah diperbarui: {$recordTitle}",
                'additional_data' => [
                    'model' => $modelName,
                    'title' => $recordTitle,
                ]
            ],
            $url,
            $modelName
        );
    }

    /**
     * Create notification for data deletion.
     */
    protected function notifyDataDeleted(string $modelName, string $recordTitle): void
    {
        $this->createNotification(
            NotificationType::DATA_DELETED,
            [
                'message' => "Data {$modelName} telah dihapus: {$recordTitle}",
                'additional_data' => [
                    'model' => $modelName,
                    'title' => $recordTitle,
                ]
            ]
        );
    }

    /**
     * Create notification for profile update.
     */
    protected function notifyProfileUpdated(): void
    {
        $this->createNotification(
            NotificationType::PROFILE_UPDATED,
            [
                'message' => 'Profil Anda telah diperbarui',
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
     * Create notification for transaction.
     */
    protected function notifyTransaction(string $action, string $description, ?string $url = null): void
    {
        $type = match($action) {
            'created' => NotificationType::TRANSACTION_CREATED,
            'completed' => NotificationType::TRANSACTION_COMPLETED,
            'failed' => NotificationType::TRANSACTION_FAILED,
            'cancelled' => NotificationType::TRANSACTION_CANCELLED,
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

    /**
     * Create notification for export/import.
     */
    protected function notifyExportImport(string $action, string $type, int $count, ?string $url = null): void
    {
        $notificationType = match($action) {
            'export_completed' => NotificationType::EXPORT_COMPLETED,
            'import_completed' => NotificationType::IMPORT_COMPLETED,
            'import_failed' => NotificationType::IMPORT_FAILED,
            default => NotificationType::EXPORT_COMPLETED,
        };

        $message = match($action) {
            'export_completed' => "Export {$type} selesai. {$count} data berhasil di-export.",
            'import_completed' => "Import {$type} selesai. {$count} data berhasil di-import.",
            'import_failed' => "Import {$type} gagal. Silakan periksa format file.",
            default => "Proses {$action} telah selesai.",
        };

        $this->createNotification(
            $notificationType,
            [
                'message' => $message,
                'additional_data' => [
                    'type' => $type,
                    'count' => $count,
                ]
            ],
            $url
        );
    }

    /**
     * Create notification for project/IKM/COTS specific events.
     */
    protected function notifyContentEvent(string $event, string $contentType, string $title, ?string $url = null): void
    {
        $type = match($event) {
            'created' => match($contentType) {
                'project' => NotificationType::PROJECT_CREATED,
                'ikm' => NotificationType::IKM_CREATED,
                'cots' => NotificationType::COTS_CREATED,
                default => NotificationType::DATA_CREATED,
            },
            'updated' => match($contentType) {
                'project' => NotificationType::PROJECT_UPDATED,
                'ikm' => NotificationType::IKM_UPDATED,
                'cots' => NotificationType::COTS_UPDATED,
                default => NotificationType::DATA_UPDATED,
            },
            'deleted' => match($contentType) {
                'project' => NotificationType::PROJECT_DELETED,
                'ikm' => NotificationType::IKM_DELETED,
                'cots' => NotificationType::COTS_DELETED,
                default => NotificationType::DATA_DELETED,
            },
            default => NotificationType::DATA_CREATED,
        };

        $verb = match($event) {
            'created' => 'baru telah ditambahkan',
            'updated' => 'telah diperbarui',
            'deleted' => 'telah dihapus',
            default => 'telah diproses',
        };

        $this->createNotification(
            $type,
            [
                'message' => "{$contentType} {$title} {$verb}",
                'additional_data' => [
                    'content_type' => $contentType,
                    'title' => $title,
                ]
            ],
            $url,
            $contentType
        );
    }
}
