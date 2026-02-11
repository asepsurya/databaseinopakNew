<?php

namespace App\Observers;

use App\Enums\NotificationType;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationObserver
{
    protected NotificationService $notificationService;

    // Models that should trigger notifications
    protected array $trackedModels = [
        'Project' => [
            'created' => NotificationType::PROJECT_CREATED,
            'updated' => NotificationType::PROJECT_UPDATED,
            'deleted' => NotificationType::PROJECT_DELETED,
        ],
        'Ikm' => [
            'created' => NotificationType::IKM_CREATED,
            'updated' => NotificationType::IKM_UPDATED,
            'deleted' => NotificationType::IKM_DELETED,
        ],
        'Cots' => [
            'created' => NotificationType::COTS_CREATED,
            'updated' => NotificationType::COTS_UPDATED,
            'deleted' => NotificationType::COTS_DELETED,
        ],
        'BencmarkProduk' => [
            'created' => NotificationType::BENCHMARK_CREATED,
            'updated' => NotificationType::BENCHMARK_UPDATED,
            'deleted' => NotificationType::BENCHMARK_DELETED,
        ],
        'DokumentasiCots' => [
            'created' => NotificationType::DOKUMENTASI_CREATED,
            'updated' => NotificationType::DOKUMENTASI_UPDATED,
            'deleted' => NotificationType::DOKUMENTASI_DELETED,
        ],
        'ProdukDesign' => [
            'created' => NotificationType::PRODUK_DESIGN_CREATED,
            'updated' => NotificationType::PRODUK_DESIGN_UPDATED,
            'deleted' => NotificationType::PRODUK_DESIGN_DELETED,
        ],
    ];

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the model "created" event.
     */
    public function created($model): void
    {
        $modelName = class_basename($model);

        if (isset($this->trackedModels[$modelName])) {
            $this->createNotification($model, 'created');
        }

        // Log activity for any model with audit trail
        $this->logActivity($model, 'CREATE');
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated($model): void
    {
        $modelName = class_basename($model);

        if (isset($this->trackedModels[$modelName])) {
            $this->createNotification($model, 'updated');
        }

        // Log activity for any model with audit trail
        $this->logActivity($model, 'UPDATE');
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted($model): void
    {
        $modelName = class_basename($model);

        if (isset($this->trackedModels[$modelName])) {
            $this->createNotification($model, 'deleted');
        }

        // Log activity for any model with audit trail
        $this->logActivity($model, 'DELETE');
    }

    /**
     * Handle the model "restored" event.
     */
    public function restored($model): void
    {
        $modelName = class_basename($model);

        $this->createNotification($model, 'restored');

        $this->logActivity($model, 'RESTORE');
    }

    /**
     * Handle the model "force deleted" event.
     */
    public function forceDeleted($model): void
    {
        $modelName = class_basename($model);

        if (isset($this->trackedModels[$modelName])) {
            $this->createNotification($model, 'deleted');
        }

        $this->logActivity($model, 'FORCE_DELETE');
    }

    /**
     * Create a notification for the model event.
     */
    protected function createNotification($model, string $event): void
    {
        $modelName = class_basename($model);
        $config = $this->trackedModels[$modelName] ?? null;

        if (!$config || !isset($config[$event])) {
            return;
        }

        $type = $config[$event];
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Get model display name and title
        $displayName = $this->getModelDisplayName($model);
        $title = $this->getModelTitle($model);
        $url = $this->getModelUrl($model);

        // Get changes if updated
        $changes = [];
        if ($event === 'updated') {
            $changes = $model->getDirty();
        }

        // Build notification data
        $data = [
            'message' => $this->getNotificationMessage($event, $modelName, $displayName),
            'additional_data' => [
                'model' => $modelName,
                'model_id' => $model->id,
                'title' => $title,
                'event' => $event,
                'changes' => $changes,
            ],
        ];

        // Create the notification
        $this->notificationService->create(
            $user,
            $type,
            $data,
            $url,
            $modelName,
            $model->id
        );

        // If this is an update, also notify about specific field changes
        if ($event === 'updated' && !empty($changes)) {
            $this->notifyFieldChanges($model, $changes, $user);
        }
    }

    /**
     * Get the display name for the model.
     */
    protected function getModelDisplayName($model): string
    {
        // Check for common display name methods/attributes
        if (method_exists($model, 'getDisplayName')) {
            return $model->getDisplayName();
        }

        if (method_exists($model, 'getName')) {
            return $model->getName();
        }

        if (isset($model->name)) {
            return $model->name;
        }

        if (isset($model->title)) {
            return $model->title;
        }

        if (isset($model->nama)) {
            return $model->nama;
        }

        if (isset($model->nama_produk)) {
            return $model->nama_produk;
        }

        if (isset($model->nama_ikm)) {
            return $model->nama_ikm;
        }

        return 'Item #' . ($model->id ?? 'Unknown');
    }

    /**
     * Get the title for the model.
     */
    protected function getModelTitle($model): string
    {
        if (method_exists($model, 'getNotificationTitle')) {
            return $model->getNotificationTitle();
        }

        return $this->getModelDisplayName($model);
    }

    /**
     * Get the URL for the model.
     */
    protected function getModelUrl($model): ?string
    {
        if (method_exists($model, 'getNotificationUrl')) {
            return $model->getNotificationUrl();
        }

        // Generate URL based on model type
        $modelName = class_basename($model);
        $id = $model->id;

        return match ($modelName) {
            'Project' => "/project",
            'Ikm' => "/project/dataikm/{$id}",
            'Cots' => "/cots",
            'BencmarkProduk' => "/project/ikms/{$id}/bencmark",
            'DokumentasiCots' => "/project/ikms/{$id}/dokumentasi",
            'ProdukDesign' => "/project/ikms/{$id}/desain",
            default => null,
        };
    }

    /**
     * Get the notification message.
     */
    protected function getNotificationMessage(string $event, string $modelName, string $displayName): string
    {
        $modelLabel = $this->getModelLabel($modelName);

        return match ($event) {
            'created' => "{$modelLabel} baru telah ditambahkan: {$displayName}",
            'updated' => "{$modelLabel} telah diperbarui: {$displayName}",
            'deleted' => "{$modelLabel} telah dihapus: {$displayName}",
            'restored' => "{$modelLabel} telah dipulihkan: {$displayName}",
            default => "{$modelLabel} telah diproses: {$displayName}",
        };
    }

    /**
     * Get the model label.
     */
    protected function getModelLabel(string $modelName): string
    {
        return match ($modelName) {
            'Project' => 'Proyek',
            'Ikm' => 'Data IKM',
            'Cots' => 'Data COTS',
            'BencmarkProduk' => 'Benchmark Produk',
            'DokumentasiCots' => 'Dokumentasi COTS',
            'ProdukDesign' => 'Desain Produk',
            default => $modelName,
        };
    }

    /**
     * Notify about specific field changes.
     */
    protected function notifyFieldChanges($model, array $changes, $user): void
    {
        // Only notify for important fields
        $importantFields = ['status', 'is_active', 'is_approved', 'verified_at'];

        foreach ($changes as $field => $value) {
            if (in_array($field, $importantFields)) {
                $this->notifyFieldChange($model, $field, $value, $user);
            }
        }
    }

    /**
     * Notify about a specific field change.
     */
    protected function notifyFieldChange($model, string $field, $value, $user): void
    {
        $modelName = class_basename($model);
        $displayName = $this->getModelDisplayName($model);

        // Determine notification type based on field
        $type = match ($field) {
            'is_approved', 'status' => $value ? NotificationType::FORM_APPROVED : NotificationType::FORM_REJECTED,
            'verified_at' => $value ? NotificationType::IKM_VERIFIED : null,
            'is_active' => $value ? NotificationType::DATA_UPDATED : NotificationType::DATA_DELETED,
            default => NotificationType::DATA_UPDATED,
        };

        if (!$type) {
            return;
        }

        $message = match ($field) {
            'is_approved' => $value
                ? "{$modelName} {$displayName} telah disetujui"
                : "{$modelName} {$displayName} telah ditolak",
            'status' => "Status {$modelName} {$displayName} diubah menjadi {$value}",
            'verified_at' => "{$modelName} {$displayName} telah diverifikasi",
            'is_active' => $value
                ? "{$modelName} {$displayName} diaktifkan"
                : "{$modelName} {$displayName} dinonaktifkan",
            default => "{$field} dari {$modelName} {$displayName} telah diubah",
        };

        $this->notificationService->create(
            $user,
            $type,
            [
                'message' => $message,
                'additional_data' => [
                    'model' => $modelName,
                    'model_id' => $model->id,
                    'field' => $field,
                    'new_value' => $value,
                ],
            ],
            $this->getModelUrl($model),
            $modelName,
            $model->id
        );
    }

    /**
     * Log activity to the activity log table.
     */
    protected function logActivity($model, string $action): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        try {
            // Create activity log entry
            DB::table('activity_logs')->insert([
                'user_id' => $user->id,
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'properties' => json_encode([
                    'changes' => $model->getDirty(),
                    'old' => $model->getOriginal(),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if activity log table doesn't exist
            // This is just for tracking, not critical
        }
    }
}
