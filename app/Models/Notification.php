<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use App\Enums\NotificationType;

class Notification extends DatabaseNotification
{
    use HasFactory;

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
        'url',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Removed $with = ['notifiable'] as morphTo() cannot be eager loaded

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Get the source entity (if any).
     */
    public function source()
    {
        return $this->morphTo('source_type', 'source_id');
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): void
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    /**
     * Determine if the notification has been read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if the notification is unread.
     */
    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    /**
     * Get the icon for the notification type.
     */
    public function getIconAttribute(): string
    {
        $type = NotificationType::tryFrom($this->type);

        if ($type) {
            return $type->getIcon();
        }

        return 'ti ti-bell';
    }

    /**
     * Get the color for the notification type.
     */
    public function getColorAttribute(): string
    {
        $type = NotificationType::tryFrom($this->type);

        if ($type) {
            return $type->getColor();
        }

        return 'secondary';
    }

    /**
     * Get the title for the notification type.
     */
    public function getTitleAttribute(): string
    {
        $type = NotificationType::tryFrom($this->type);

        if ($type) {
            return $type->getTitle();
        }

        return 'Notifikasi';
    }

    /**
     * Get a human-readable time ago string.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->isoFormat('D MMMM Y, HH:mm');
    }

    /**
     * Get a brief summary of the notification.
     */
    public function getSummaryAttribute(): string
    {
        $data = $this->data ?? [];

        return $data['message'] ?? $data['title'] ?? 'Tidak ada detail';
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to order by most recent.
     */
    public function scopeMostRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, NotificationType $type)
    {
        return $query->where('type', $type->value);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeOfCategory($query, string $category)
    {
        $types = NotificationType::cases();
        $typeValues = array_filter($types, fn($t) => $t->getCategory() === $category);

        return $query->whereIn('type', array_map(fn($t) => $t->value, $typeValues));
    }
}
