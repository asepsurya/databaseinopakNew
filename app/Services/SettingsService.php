<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\LogoSetting;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SettingsService
{
    /**
     * Allowed image extensions for logo upload.
     */
    public const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg', 'svg', 'ico', 'gif', 'webp'];

    /**
     * Maximum file size in bytes (2MB).
     */
    public const MAX_FILE_SIZE = 2 * 1024 * 1024;

    /**
     * Logo upload directory.
     */
    public const LOGO_DIR = 'settings/logos';

    /**
     * Cache key for settings.
     */
    public const CACHE_KEY_SETTINGS = 'app_settings';
    public const CACHE_KEY_LOGOS = 'app_logos';
    public const CACHE_TTL = 0; // No caching - always fetch fresh

    /**
     * Get all logo settings.
     */
    public function getAllLogos(): array
    {
        $logoTypes = ['header', 'footer', 'sidebar', 'login', 'favicon'];
        $logos = [];

        foreach ($logoTypes as $type) {
            $logos[$type] = LogoSetting::getByType($type);
        }

        return $logos;
    }

    /**
     * Get logo by type.
     */
    public function getLogo(string $type): LogoSetting
    {
        return LogoSetting::getByType($type);
    }

    /**
     * Upload logo image.
     */
    public function uploadLogo(Request $request, string $logoType): array
    {
        $request->validate([
            'logo' => [
                'required',
                'file',
                'image',
                'mimes:' . implode(',', self::ALLOWED_EXTENSIONS),
                'max:' . (self::MAX_FILE_SIZE / 1024),
            ],
        ]);

        $file = $request->file('logo');
        $extension = $file->getClientOriginalExtension();
        $filename = $logoType . '_' . Str::random(10) . '.' . $extension;

        // Create directory if it doesn't exist
        $directory = storage_path('app/public/' . self::LOGO_DIR);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Store the file
        $path = $file->storeAs(self::LOGO_DIR, $filename, 'public');

        // Return relative path (Storage::url returns absolute URL with domain)
        return [
            'path' => $path,
            'url' => 'storage/' . $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $extension,
        ];
    }

    /**
     * Update logo settings.
     */
    public function updateLogo(Request $request, string $logoType): LogoSetting
    {
        $logo = LogoSetting::getByType($logoType);

        // Handle file upload if present
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($logo->image_path && Storage::exists($logo->image_path)) {
                Storage::delete($logo->image_path);
            }

            $uploaded = $this->uploadLogo($request, $logoType);
            $logo->image_path = $uploaded['path'];
            $logo->image_url = $uploaded['url'];
        }

        // Update other fields
        $logo->name = $request->input('name', $logo->name ?? LogoSetting::DEFAULT_LOGOS[$logoType]['name'] ?? $logoType);
        $logo->width = $request->input('width') ? (int) $request->input('width') : null;
        $logo->height = $request->input('height') ? (int) $request->input('height') : null;
        $logo->alignment = $request->input('alignment', 'left');
        $logo->position = $request->input('position', 'default');
        $logo->is_active = $request->boolean('is_active', true);
        $logo->custom_css = $request->input('custom_css');

        $logo->save();

        // Clear related caches
        $this->clearSettingsCache();

        // Log the activity
        ActivityLog::log(
            auth()->user(),
            'logo_updated',
            LogoSetting::class,
            $logo->id,
            [
                'logo_type' => $logoType,
                'changes' => $logo->getDirty(),
            ]
        );

        return $logo;
    }

    /**
     * Reset logo to default.
     */
    public function resetLogo(string $logoType): bool
    {
        $logo = LogoSetting::getByType($logoType);

        // Delete uploaded file if exists
        if ($logo->image_path && Storage::exists($logo->image_path)) {
            Storage::delete($logo->image_path);
        }

        $result = $logo->resetToDefault();

        if ($result) {
            // Clear related caches
            $this->clearSettingsCache();

            ActivityLog::log(
                auth()->user(),
                'logo_reset',
                LogoSetting::class,
                $logo->id,
                ['logo_type' => $logoType]
            );
        }

        return $result;
    }

    /**
     * Get a general setting.
     */
    public function getSetting(string $key, $default = null)
    {
        // Always fetch fresh from database - no caching
        return AppSetting::get($key, $default);
    }

    /**
     * Update general setting.
     */
    public function updateSetting(Request $request, string $key): AppSetting
    {
        $type = $request->input('type', 'string');
        $description = $request->input('description');

        $value = match ($type) {
            'boolean' => $request->boolean('value') ? 'true' : 'false',
            'integer' => (string) $request->input('value'),
            'json', 'array' => json_encode($request->input('value')),
            default => (string) $request->input('value'),
        };

        $setting = AppSetting::set($key, $value, $type, $description);

        // Clear settings cache
        $this->clearSettingsCache();

        ActivityLog::log(
            auth()->user(),
            'setting_updated',
            AppSetting::class,
            $setting->id,
            [
                'key' => $key,
                'value' => $value,
            ]
        );

        return $setting;
    }

    /**
     * Check if registration is enabled.
     */
    public function isRegistrationEnabled(): bool
    {
        // Always fetch fresh from database
        return AppSetting::get('registration_enabled', true);
    }

    /**
     * Enable or disable registration.
     */
    public function setRegistrationEnabled(bool $enabled): AppSetting
    {
        $setting = AppSetting::set(
            'registration_enabled',
            $enabled ? 'true' : 'false',
            'boolean',
            'Enable or disable user registration'
        );

        // Clear settings cache
        $this->clearSettingsCache();

        ActivityLog::log(
            auth()->user(),
            'registration_toggled',
            AppSetting::class,
            $setting->id,
            [
                'key' => 'registration_enabled',
                'value' => $enabled,
            ]
        );

        return $setting;
    }

    /**
     * Get activity logs for settings.
     */
    public function getActivityLogs(int $perPage = 20)
    {
        return ActivityLog::whereIn('action', [
            'logo_updated',
            'logo_reset',
            'setting_updated',
            'registration_toggled',
        ])->with('user')->latest()->paginate($perPage);
    }

    /**
     * Clear all settings-related caches.
     */
    public function clearSettingsCache(): void
    {
        // Clear any application cache
        Cache::flush();
    }

    /**
     * Seed default logo settings.
     */
    public function seedDefaultLogos(): void
    {
        $defaults = LogoSetting::DEFAULT_LOGOS;

        foreach ($defaults as $type => $config) {
            LogoSetting::updateOrCreate(
                ['logo_type' => $type],
                [
                    'name' => $config['name'],
                    'is_active' => true,
                    'alignment' => 'left',
                    'position' => 'default',
                ]
            );
        }

        // Seed default app settings
        AppSetting::set('registration_enabled', 'true', 'boolean', 'Enable or disable user registration');
        AppSetting::set('app_name', 'Database INOPAK', 'string', 'Application name');
        AppSetting::set('app_version', '1.0.0', 'string', 'Application version');
    }
}
