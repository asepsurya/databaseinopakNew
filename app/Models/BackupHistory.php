<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BackupHistory extends Model
{
    protected $table = 'backup_histories';

    protected $fillable = [
        'filename',
        'path',
        'format',
        'type',
        'tables_included',
        'file_size',
        'file_size_human',
        'is_encrypted',
        'status',
        'error_message',
        'started_at',
        'completed_at',
        'triggered_by',
        'user_id',
    ];

    protected $casts = [
        'tables_included' => 'array',
        'is_encrypted' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who triggered the backup
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human readable file size
     */
    public function getFileSizeHuman(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get full file path
     */
    public function getFullPath(): string
    {
        // Use Storage to get the correct path based on filesystem config
        return Storage::disk(config('filesystems.default', 'local'))->path($this->path . '/' . $this->filename);
    }

    /**
     * Check if file exists
     */
    public function fileExists(): bool
    {
        return Storage::disk(config('filesystems.default', 'local'))->exists($this->path . '/' . $this->filename);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'in_progress' => 'bg-warning',
            'pending' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get tables count
     */
    public function getTablesCount(): int
    {
        if ($this->tables_included && is_array($this->tables_included)) {
            return count($this->tables_included);
        }
        return 0;
    }

    /**
     * Scope for completed backups
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed backups
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for scheduled backups
     */
    public function scopeScheduled($query)
    {
        return $query->where('triggered_by', 'scheduled');
    }

    /**
     * Scope for manual backups
     */
    public function scopeManual($query)
    {
        return $query->where('triggered_by', 'manual');
    }
}
