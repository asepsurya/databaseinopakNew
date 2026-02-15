<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupSetting extends Model
{
    protected $table = 'backup_settings';

    protected $fillable = [
        'auto_backup_enabled',
        'frequency',
        'daily_time',
        'monthly_day',
        'monthly_time',
        'backup_path',
        'encryption_enabled',
        'encryption_password',
        'retention_days',
        'auto_delete_old',
        'default_format',
    ];

    protected $casts = [
        'auto_backup_enabled' => 'boolean',
        'encryption_enabled' => 'boolean',
        'auto_delete_old' => 'boolean',
        'daily_time' => 'datetime:H:i:s',
        'monthly_time' => 'datetime:H:i:s',
    ];

    /**
     * Get the singleton instance of backup settings
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'auto_backup_enabled' => false,
                'frequency' => 'daily',
                'daily_time' => '02:00:00',
                'monthly_day' => 1,
                'monthly_time' => '02:00:00',
                'backup_path' => 'backups',
                'encryption_enabled' => false,
                'retention_days' => 30,
                'auto_delete_old' => true,
                'default_format' => 'sql',
            ]);
        }

        return $settings;
    }

    /**
     * Check if auto backup is enabled
     */
    public function isAutoBackupEnabled(): bool
    {
        return $this->auto_backup_enabled;
    }

    /**
     * Get formatted daily time
     */
    public function getDailyTimeFormatted(): string
    {
        return $this->daily_time ? $this->daily_time->format('H:i') : '02:00';
    }

    /**
     * Get formatted monthly time
     */
    public function getMonthlyTimeFormatted(): string
    {
        return $this->monthly_time ? $this->monthly_time->format('H:i') : '02:00';
    }
}
