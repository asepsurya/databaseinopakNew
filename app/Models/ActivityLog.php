<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity.
     */
    public static function log(
        ?User $user,
        string $action,
        string $modelType,
        $modelId = null,
        array $properties = [],
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return static::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'properties' => $properties,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }

    /**
     * Get recent activities for a user.
     */
    public static function getRecentForUser(User $user, int $limit = 20)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by model.
     */
    public static function getByModel(string $modelType, $modelId, int $limit = 50)
    {
        return static::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get all activities with pagination.
     */
    public static function getAll(int $perPage = 20)
    {
        return static::with('user')
            ->latest()
            ->paginate($perPage);
    }
}
