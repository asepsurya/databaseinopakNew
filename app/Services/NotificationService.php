<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class NotificationService
{
    /**
     * Create a new notification.
     */
    public function create(
        User $user,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): Notification {
        // Check if user has disabled this type of notification
        $preference = NotificationPreference::where('user_id', $user->id)
            ->where('notification_type', $type->value)
            ->first();

        if ($preference && !$preference->enabled) {
            // User has disabled this type, but we still create it
            // as they might want to see it in their preferences later
        }

        // Merge type information into data
        $notificationData = array_merge([
            'icon' => $type->getIcon(),
            'color' => $type->getColor(),
            'title' => $type->getTitle(),
            'message' => $data['message'] ?? $type->getTitle(),
            'status' => $type->isSuccess() ? 'success' : ($type->isFailure() ? 'failure' : 'info'),
        ], $data);

        // Create the notification
        $notification = Notification::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => $type->value,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => $notificationData,
            'url' => $url,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);

        // Fire the notification created event for broadcasting
        try {
            Event::dispatch(new NotificationCreated($notification, $user));
        } catch (\Exception $e) {
            // Broadcasting might not be configured, silently fail
        }

        return $notification;
    }

    /**
     * Create a notification for multiple users.
     */
    public function createForUsers(
        array $users,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        foreach ($users as $user) {
            if ($user instanceof User) {
                $this->create($user, $type, $data, $url, $sourceType, $sourceId);
            }
        }
    }

    /**
     * Create a notification for all users except the given one.
     */
    public function createForAllExcept(
        User $exceptUser,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $users = User::where('id', '!=', $exceptUser->id)->get();
        $this->createForUsers($users, $type, $data, $url, $sourceType, $sourceId);
    }

    /**
     * Create a notification for a role.
     */
    public function createForRole(
        string $role,
        NotificationType $type,
        array $data = [],
        ?string $url = null,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void
    {
        $users = User::whereHas('role', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();

        $this->createForUsers($users, $type, $data, $url, $sourceType, $sourceId);
    }

    /**
     * Get unread notification count for a user.
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Get recent notifications for a user.
     */
    public function getRecent(User $user, int $limit = 10)
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get notifications by type for a user.
     */
    public function getByType(User $user, NotificationType $type, int $limit = 20)
    {
        return $user->notifications()
            ->where('type', $type->value)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get notifications by category for a user.
     */
    public function getByCategory(User $user, string $category, int $limit = 20)
    {
        $types = NotificationType::cases();
        $typeValues = array_filter($types, fn($t) => $t->getCategory() === $category);
        $typeValues = array_map(fn($t) => $t->value, $typeValues);

        return $user->notifications()
            ->whereIn('type', $typeValues)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(User $user): int
    {
        return $user->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark a specific notification as unread.
     */
    public function markAsUnread(Notification $notification): void
    {
        $notification->markAsUnread();
    }

    /**
     * Mark notifications as read by type.
     */
    public function markAllAsReadByType(User $user, NotificationType $type): int
    {
        return $user->unreadNotifications()
            ->where('type', $type->value)
            ->update(['read_at' => now()]);
    }

    /**
     * Mark notifications as read by category.
     */
    public function markAllAsReadByCategory(User $user, string $category): int
    {
        $types = NotificationType::cases();
        $typeValues = array_filter($types, fn($t) => $t->getCategory() === $category);
        $typeValues = array_map(fn($t) => $t->value, $typeValues);

        return $user->unreadNotifications()
            ->whereIn('type', $typeValues)
            ->update(['read_at' => now()]);
    }

    /**
     * Delete a notification.
     */
    public function delete(Notification $notification): bool
    {
        return $notification->delete();
    }

    /**
     * Delete old notifications.
     */
    public function deleteOlderThan(int $days = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Delete all notifications for a user.
     */
    public function deleteAllForUser(User $user): int
    {
        return $user->notifications()->delete();
    }

    /**
     * Delete read notifications for a user.
     */
    public function deleteReadForUser(User $user): int
    {
        return $user->notifications()->whereNotNull('read_at')->delete();
    }

    /**
     * Initialize default preferences for a user.
     */
    public function initializePreferences(User $user): void
    {
        foreach (NotificationType::cases() as $type) {
            NotificationPreference::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'notification_type' => $type->value,
                ],
                [
                    'enabled' => true,
                    'frequency' => 'realtime',
                ]
            );
        }
    }

    /**
     * Update a user's notification preference.
     */
    public function updatePreference(
        User $user,
        NotificationType $type,
        bool $enabled,
        string $frequency = 'realtime'
    ): NotificationPreference
    {
        return NotificationPreference::updateOrCreate(
            [
                'user_id' => $user->id,
                'notification_type' => $type->value,
            ],
            [
                'enabled' => $enabled,
                'frequency' => $frequency,
            ]
        );
    }

    /**
     * Get user's notification preferences.
     */
    public function getPreferences(User $user)
    {
        return NotificationPreference::where('user_id', $user->id)->get();
    }

    /**
     * Check if a notification type is enabled for a user.
     */
    public function isEnabled(User $user, NotificationType $type): bool
    {
        $preference = NotificationPreference::where('user_id', $user->id)
            ->where('notification_type', $type->value)
            ->first();

        if (!$preference) {
            // Default to enabled if no preference exists
            return true;
        }

        return $preference->enabled;
    }

    /**
     * Get notification statistics for a user.
     */
    public function getStatistics(User $user): array
    {
        $total = $user->notifications()->count();
        $unread = $user->unreadNotifications()->count();
        $read = $total - $unread;

        // Get counts by category
        $categories = [];
        foreach (['authentication', 'data_operation', 'form', 'profile', 'project', 'ikm', 'cots', 'system', 'user_activity'] as $category) {
            $types = NotificationType::cases();
            $typeValues = array_filter($types, fn($t) => $t->getCategory() === $category);
            $typeValues = array_map(fn($t) => $t->value, $typeValues);

            $categories[$category] = $user->notifications()
                ->whereIn('type', $typeValues)
                ->count();
        }

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $read,
            'by_category' => $categories,
        ];
    }

    /**
     * Search notifications for a user.
     */
    public function search(User $user, string $search, int $limit = 20)
    {
        return $user->notifications()
            ->where(function ($query) use ($search) {
                $query->where('data->message', 'like', "%{$search}%")
                    ->orWhere('data->title', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity timeline for a user.
     */
    public function getActivityTimeline(User $user, int $days = 7)
    {
        return $user->notifications()
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($notification) {
                return $notification->created_at->format('Y-m-d');
            });
    }

    /**
     * Create a log entry for audit trail.
     */
    public function logActivity(
        User $user,
        string $action,
        string $modelType,
        ?int $modelId = null,
        array $properties = []
    ): void
    {
        try {
            DB::table('activity_logs')->insert([
                'user_id' => $user->id,
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'properties' => json_encode($properties),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if activity log table doesn't exist
        }
    }

    /**
     * Get recent activities for audit trail.
     */
    public function getRecentActivities(User $user, int $limit = 50)
    {
        try {
            return DB::table('activity_logs')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
}
