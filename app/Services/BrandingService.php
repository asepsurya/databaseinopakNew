<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\LogoSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BrandingService
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
    public const LOGO_DIR = 'settings/branding';

    /**
     * All branding elements that can be configured.
     */
    public const BRANDING_ELEMENTS = [
        'logo' => [
            'name' => 'Logo Utama',
            'type' => 'image',
            'positions' => ['header', 'footer', 'sidebar', 'login'],
        ],
        'favicon' => [
            'name' => 'Favicon',
            'type' => 'image',
            'positions' => ['browser'],
        ],
        'og_image' => [
            'name' => 'OG Image (Social Media)',
            'type' => 'image',
            'positions' => ['social'],
        ],
        'auth_background' => [
            'name' => 'Background Halaman Login',
            'type' => 'image',
            'positions' => ['auth'],
        ],
        'company_logo' => [
            'name' => 'Logo Perusahaan',
            'type' => 'image',
            'positions' => ['footer', 'reports'],
        ],
    ];

    /**
     * All text-based settings.
     */
    public const TEXT_ELEMENTS = [
        'app_name' => [
            'name' => 'Nama Aplikasi',
            'type' => 'text',
            'default' => 'Database INOPAK',
        ],
        'app_tagline' => [
            'name' => 'Tagline',
            'type' => 'text',
            'default' => 'Sistem Pengelolaan Informasi',
        ],
        'company_name' => [
            'name' => 'Nama Perusahaan',
            'type' => 'text',
            'default' => 'INOPAK',
        ],
        'copyright_text' => [
            'name' => 'Teks Copyright',
            'type' => 'text',
            'default' => '© 2024 INOPAK. All rights reserved.',
        ],
        'meta_description' => [
            'name' => 'Meta Description',
            'type' => 'textarea',
            'default' => 'Database INOPAK - Sistem Pengelolaan Informasi',
        ],
        'meta_keywords' => [
            'name' => 'Meta Keywords',
            'type' => 'text',
            'default' => 'inopak, database, ikm, admin dashboard',
        ],
    ];

    /**
     * All boolean/toggle settings.
     */
    public const TOGGLE_ELEMENTS = [
        'registration_enabled' => [
            'name' => 'Aktifkan Pendaftaran',
            'type' => 'boolean',
            'default' => true,
        ],
        'show_branding' => [
            'name' => 'Tampilkan Branding',
            'type' => 'boolean',
            'default' => true,
        ],
        'dark_mode_default' => [
            'name' => 'Mode Gelap Default',
            'type' => 'boolean',
            'default' => false,
        ],
    ];

    /**
     * Get all branding settings as a single configuration array.
     */
    public function getAllBranding(): array
    {
        $branding = [
            'images' => $this->getAllImages(),
            'texts' => $this->getAllTexts(),
            'toggles' => $this->getAllToggles(),
        ];

        return $branding;
    }

    /**
     * Get all image configurations.
     */
    public function getAllImages(): array
    {
        $images = [];
        $logoTypes = LogoSetting::pluck('logo_type')->toArray();

        foreach (self::BRANDING_ELEMENTS as $key => $config) {
            $logo = LogoSetting::getByType($key);
            $images[$key] = [
                'name' => $config['name'],
                'type' => $config['type'],
                'url' => $logo->getUrl(),
                'default_url' => $logo->getDefaultUrl(),
                'is_custom' => !empty($logo->image_url),
                'is_active' => $logo->is_active,
                'width' => $logo->width,
                'height' => $logo->height,
                'alignment' => $logo->alignment,
                'positions' => $config['positions'],
            ];
        }

        return $images;
    }

    /**
     * Get all text configurations.
     */
    public function getAllTexts(): array
    {
        $texts = [];

        foreach (self::TEXT_ELEMENTS as $key => $config) {
            $texts[$key] = [
                'name' => $config['name'],
                'type' => $config['type'],
                'value' => AppSetting::get($key, $config['default']),
            ];
        }

        return $texts;
    }

    /**
     * Get all toggle configurations.
     */
    public function getAllToggles(): array
    {
        $toggles = [];

        foreach (self::TOGGLE_ELEMENTS as $key => $config) {
            $toggles[$key] = [
                'name' => $config['name'],
                'type' => $config['type'],
                'value' => AppSetting::get($key, $config['default']),
            ];
        }

        return $toggles;
    }

    /**
     * Get a single image/logo by type.
     */
    public function getImage(string $type): LogoSetting
    {
        return LogoSetting::getByType($type);
    }

    /**
     * Get a text setting by key.
     */
    public function getText(string $key, $default = null)
    {
        $config = self::TEXT_ELEMENTS[$key] ?? null;
        if (!$config) {
            return $default;
        }

        return AppSetting::get($key, $config['default']);
    }

    /**
     * Get a toggle setting by key.
     */
    public function getToggle(string $key, $default = null)
    {
        $config = self::TOGGLE_ELEMENTS[$key] ?? null;
        if (!$config) {
            return $default;
        }

        return AppSetting::get($key, $config['default']);
    }

    /**
     * Upload and update an image.
     */
    public function updateImage(Request $request, string $type): LogoSetting
    {
        $logo = LogoSetting::getByType($type);

        // Handle file upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($logo->image_path && Storage::exists($logo->image_path)) {
                Storage::delete($logo->image_path);
            }

            $uploaded = $this->uploadImage($request->file('image'), $type);
            $logo->image_path = $uploaded['path'];
            $logo->image_url = $uploaded['url'];
        }

        // Update other fields
        $logo->name = $request->input('name', $logo->name ?? self::BRANDING_ELEMENTS[$type]['name'] ?? $type);
        $logo->width = $request->input('width') ? (int) $request->input('width') : null;
        $logo->height = $request->input('height') ? (int) $request->input('height') : null;
        $logo->alignment = $request->input('alignment', 'center');
        $logo->is_active = $request->boolean('is_active', true);

        $logo->save();

        // Log activity
        ActivityLog::log(
            auth()->user(),
            'branding_image_updated',
            LogoSetting::class,
            $logo->id,
            ['element' => $type]
        );

        return $logo;
    }

    /**
     * Update a text setting.
     */
    public function updateText(Request $request, string $key): AppSetting
    {
        $config = self::TEXT_ELEMENTS[$key] ?? null;
        if (!$config) {
            throw new \InvalidArgumentException("Invalid text setting: {$key}");
        }

        $value = match ($config['type']) {
            'textarea' => $request->input('value'),
            default => (string) $request->input('value'),
        };

        $setting = AppSetting::set($key, $value, $config['type'], $config['name']);

        ActivityLog::log(
            auth()->user(),
            'branding_text_updated',
            AppSetting::class,
            $setting->id,
            ['element' => $key, 'value' => $value]
        );

        return $setting;
    }

    /**
     * Update a toggle setting.
     */
    public function updateToggle(Request $request, string $key): AppSetting
    {
        $config = self::TOGGLE_ELEMENTS[$key] ?? null;
        if (!$config) {
            throw new \InvalidArgumentException("Invalid toggle setting: {$key}");
        }

        $value = $request->boolean('value') ? 'true' : 'false';

        $setting = AppSetting::set($key, $value, 'boolean', $config['name']);

        ActivityLog::log(
            auth()->user(),
            'branding_toggle_updated',
            AppSetting::class,
            $setting->id,
            ['element' => $key, 'value' => $value === 'true']
        );

        return $setting;
    }

    /**
     * Reset an image to default.
     */
    public function resetImage(string $type): bool
    {
        $logo = LogoSetting::getByType($type);

        // Delete uploaded file if exists
        if ($logo->image_path && Storage::exists($logo->image_path)) {
            Storage::delete($logo->image_path);
        }

        $result = $logo->resetToDefault();

        if ($result) {
            ActivityLog::log(
                auth()->user(),
                'branding_image_reset',
                LogoSetting::class,
                $logo->id,
                ['element' => $type]
            );
        }

        return $result;
    }

    /**
     * Upload an image file.
     */
    protected function uploadImage($file, string $type): array
    {
        $extension = $file->getClientOriginalExtension();
        $filename = $type . '_' . Str::random(10) . '.' . $extension;

        // Create directory if it doesn't exist
        $directory = storage_path('app/public/' . self::LOGO_DIR);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Store the file
        $path = $file->storeAs(self::LOGO_DIR, $filename, 'public');

        return [
            'path' => $path,
            'url' => Storage::url($path),
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $extension,
        ];
    }

    /**
     * Seed all default branding settings.
     */
    public function seedDefaults(): void
    {
        // Seed image settings
        foreach (self::BRANDING_ELEMENTS as $type => $config) {
            LogoSetting::updateOrCreate(
                ['logo_type' => $type],
                [
                    'name' => $config['name'],
                    'is_active' => true,
                    'alignment' => 'center',
                ]
            );
        }

        // Seed text settings
        foreach (self::TEXT_ELEMENTS as $key => $config) {
            AppSetting::set($key, $config['default'], $config['type'], $config['name']);
        }

        // Seed toggle settings
        foreach (self::TOGGLE_ELEMENTS as $key => $config) {
            $value = $config['default'] ? 'true' : 'false';
            AppSetting::set($key, $value, 'boolean', $config['name']);
        }
    }

    /**
     * Get metadata for all settings (for admin UI).
     */
    public function getMetadata(): array
    {
        return [
            'images' => self::BRANDING_ELEMENTS,
            'texts' => self::TEXT_ELEMENTS,
            'toggles' => self::TOGGLE_ELEMENTS,
        ];
    }
}
