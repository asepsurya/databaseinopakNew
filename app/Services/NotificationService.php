<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        return Notification::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => $type->value,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => array_merge([
                'icon' => $type->getIcon(),
                'color' => $type->getColor(),
                'title' => $type->getTitle(),
                'message' => $data['message'] ?? $type->getTitle(),
                'status' => $type->isSuccess() ? 'success' : ($type->isFailure() ? 'failure' : 'info'),
            ], $data),
            'url' => $url,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);
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
        // Use orderBy directly instead of mostRecent() scope
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(User $user): void
    {
        // Use the relationship method with parentheses to get the query builder
        $user->unreadNotifications()->update(['read_at' => now()]);
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
     * Delete old notifications.
     */
    public function deleteOlderThan(int $days = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($days))->delete();
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
}
