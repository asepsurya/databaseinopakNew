<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get all notifications for the current user (paginated).
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 15);
        $type = $request->input('type');
        $readStatus = $request->input('read_status');

        // Use orderBy directly on the relation instead of scope
        $query = $user->notifications()->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($readStatus === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($readStatus === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Get recent notifications for the dropdown.
     */
    public function recent(Request $request): JsonResponse
    {
        $user = Auth::user();
        $limit = $request->input('limit', 5);

        $notifications = $this->notificationService->getRecent($user, $limit);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Get unread notification count.
     */
    public function unreadCount(): JsonResponse
    {
        $user = Auth::user();
        $count = $this->notificationService->getUnreadCount($user);

        return response()->json([
            'success' => true,
            'unread_count' => $count,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Ensure the notification belongs to the user
        if ($notification->notifiable_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $this->notificationService->markAsRead($notification);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        $this->notificationService->markAllAsRead($user);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Mark a notification as unread.
     */
    public function markAsUnread(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Ensure the notification belongs to the user
        if ($notification->notifiable_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $this->notificationService->markAsUnread($notification);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread',
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Ensure the notification belongs to the user
        if ($notification->notifiable_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Delete all notifications for the user.
     */
    public function destroyAll(): JsonResponse
    {
        $user = Auth::user();
        $user->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications deleted',
        ]);
    }

    /**
     * Get notification preferences.
     */
    public function preferences(): JsonResponse
    {
        $user = Auth::user();
        $preferences = $this->notificationService->getPreferences($user);

        // Group by category
        $grouped = [];
        foreach (NotificationType::cases() as $type) {
            $category = $type->getCategory();
            $preference = $preferences->firstWhere('notification_type', $type->value);

            $grouped[$category][] = [
                'type' => $type->value,
                'title' => $type->getTitle(),
                'icon' => $type->getIcon(),
                'color' => $type->getColor(),
                'enabled' => $preference ? $preference->enabled : true,
                'frequency' => $preference ? $preference->frequency : 'realtime',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $grouped,
        ]);
    }

    /**
     * Update notification preferences.
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        $user = Auth::user();
        $preferences = $request->input('preferences', []);

        foreach ($preferences as $type => $settings) {
            if (NotificationType::tryFrom($type)) {
                $this->notificationService->updatePreference(
                    $user,
                    NotificationType::from($type),
                    $settings['enabled'] ?? true,
                    $settings['frequency'] ?? 'realtime'
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated',
        ]);
    }

    /**
     * Get notification details.
     */
    public function show(Notification $notification): JsonResponse
    {
        $user = Auth::user();

        // Ensure the notification belongs to the user
        if ($notification->notifiable_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Mark as read when viewed
        if ($notification->isUnread()) {
            $this->notificationService->markAsRead($notification);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->data['message'] ?? null,
                'icon' => $notification->icon,
                'color' => $notification->color,
                'status' => $notification->data['status'] ?? null,
                'url' => $notification->data['url'] ?? null,
                'created_at' => $notification->created_at,
                'formatted_date' => $notification->formatted_date,
                'time_ago' => $notification->time_ago,
                'is_read' => $notification->is_read,
                'additional_data' => $notification->data['additional_data'] ?? null,
            ],
        ]);
    }

    /**
     * Initialize default preferences for the current user.
     */
    public function initializePreferences(): JsonResponse
    {
        $user = Auth::user();
        $this->notificationService->initializePreferences($user);

        return response()->json([
            'success' => true,
            'message' => 'Preferences initialized',
        ]);
    }

    /**
     * Get notification statistics for the current user.
     */
    public function statistics(): JsonResponse
    {
        $user = Auth::user();
        $statistics = $this->notificationService->getStatistics($user);

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Get notifications by category.
     */
    public function byCategory(Request $request): JsonResponse
    {
        $user = Auth::user();
        $category = $request->input('category');
        $limit = $request->input('limit', 20);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category is required',
            ], 400);
        }

        $notifications = $this->notificationService->getByCategory($user, $category, $limit);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Get notifications by type.
     */
    public function byType(Request $request): JsonResponse
    {
        $user = Auth::user();
        $type = $request->input('type');
        $limit = $request->input('limit', 20);

        if (!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Type is required',
            ], 400);
        }

        $notificationType = NotificationType::tryFrom($type);

        if (!$notificationType) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid notification type',
            ], 400);
        }

        $notifications = $this->notificationService->getByType($user, $notificationType, $limit);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Search notifications.
     */
    public function search(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = $request->input('q', '');
        $limit = $request->input('limit', 20);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
            ], 400);
        }

        $notifications = $this->notificationService->search($user, $query, $limit);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Get activity timeline.
     */
    public function activityTimeline(Request $request): JsonResponse
    {
        $user = Auth::user();
        $days = $request->input('days', 7);

        $timeline = $this->notificationService->getActivityTimeline($user, $days);

        return response()->json([
            'success' => true,
            'data' => $timeline,
        ]);
    }

    /**
     * Get recent activities (audit log).
     */
    public function recentActivities(Request $request): JsonResponse
    {
        $user = Auth::user();
        $limit = $request->input('limit', 50);

        $activities = $this->notificationService->getRecentActivities($user, $limit);

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }

    /**
     * Mark all notifications as read by category.
     */
    public function markAllReadByCategory(Request $request): JsonResponse
    {
        $user = Auth::user();
        $category = $request->input('category');

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category is required',
            ], 400);
        }

        $count = $this->notificationService->markAllAsReadByCategory($user, $category);

        return response()->json([
            'success' => true,
            'message' => "{$count} notifications marked as read",
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Mark all notifications as read by type.
     */
    public function markAllReadByType(Request $request): JsonResponse
    {
        $user = Auth::user();
        $type = $request->input('type');

        if (!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Type is required',
            ], 400);
        }

        $notificationType = NotificationType::tryFrom($type);

        if (!$notificationType) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid notification type',
            ], 400);
        }

        $count = $this->notificationService->markAllAsReadByType($user, $notificationType);

        return response()->json([
            'success' => true,
            'message' => "{$count} notifications marked as read",
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Delete read notifications only.
     */
    public function deleteRead(): JsonResponse
    {
        $user = Auth::user();
        $count = $this->notificationService->deleteReadForUser($user);

        return response()->json([
            'success' => true,
            'message' => "{$count} read notifications deleted",
        ]);
    }

    /**
     * Export notifications.
     */
    public function export(Request $request): JsonResponse
    {
        $user = Auth::user();
        $format = $request->input('format', 'json');
        $type = $request->input('type');
        $readStatus = $request->input('read_status');

        $query = $user->notifications()->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($readStatus === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($readStatus === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->get();

        if ($format === 'csv') {
            $csv = $this->buildCsv($notifications);

            return response()->streamDownload(function () use ($csv) {
                echo $csv;
            }, 'notifications.csv', [
                'Content-Type' => 'text/csv',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Build CSV from notifications.
     */
    protected function buildCsv($notifications): string
    {
        $headers = ['ID', 'Type', 'Title', 'Message', 'Status', 'URL', 'Created At', 'Read At'];
        $rows = [];

        foreach ($notifications as $notification) {
            $rows[] = [
                $notification->id,
                $notification->type,
                $notification->data['title'] ?? '',
                $notification->data['message'] ?? '',
                $notification->data['status'] ?? '',
                $notification->url ?? '',
                $notification->created_at,
                $notification->read_at ?? 'Unread',
            ];
        }

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
