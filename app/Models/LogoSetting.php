<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_type',
        'name',
        'image_path',
        'image_url',
        'width',
        'height',
        'alignment',
        'position',
        'is_active',
        'custom_css',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
    ];

    /**
     * Default logo configurations.
     * All logos use the favicon (inopak/fav.png) as the primary/default logo.
     * For light/dark mode, use the same logo_dark.png from inopak folder.
     */
    public const DEFAULT_LOGOS = [
        'header' => [
            'name' => 'Header Logo',
            'default_light' => 'assets/images/inopak/logo_dark.png',
            'default_dark' => 'assets/images/inopak/logo_dark.png',
            'default_small' => 'assets/images/inopak/fav.png',
        ],
        'footer' => [
            'name' => 'Footer Logo',
            'default' => 'assets/images/inopak/logo_dark.png',
            'default_light' => 'assets/images/inopak/logo_dark.png',
            'default_dark' => 'assets/images/inopak/logo_dark.png',
        ],
        'sidebar' => [
            'name' => 'Sidebar Logo',
            'default' => 'assets/images/inopak/logo_dark.png',
            'default_light' => 'assets/images/inopak/logo_dark.png',
            'default_dark' => 'assets/images/inopak/logo_dark.png',
        ],
        'login' => [
            'name' => 'Login Page Logo',
            'default' => 'assets/images/inopak/fav.png',
            'default_light' => 'assets/images/inopak/fav.png',
            'default_dark' => 'assets/images/inopak/fav.png',
        ],
        'favicon' => [
            'name' => 'Favicon',
            'default' => 'assets/images/inopak/fav.png',
            'default_light' => 'assets/images/inopak/fav.png',
            'default_dark' => 'assets/images/inopak/fav.png',
        ],
    ];

    /**
     * Get logo by type.
     */
    public static function getByType(string $type): self
    {
        return static::where('logo_type', $type)->first() ?? new self(['logo_type' => $type]);
    }

    /**
     * Get the URL for the logo (custom or default).
     */
    public function getUrl(?string $variant = null): string
    {
        if (!$this->is_active || empty($this->image_url)) {
            return $this->getDefaultUrl($variant);
        }

        // image_url now stores relative path like 'storage/settings/logos/logo.png'
        return asset($this->image_url);
    }

    /**
     * Get default logo URL.
     */
    public function getDefaultUrl(?string $variant = null): string
    {
        $defaults = self::DEFAULT_LOGOS[$this->logo_type] ?? null;

        if (!$defaults) {
            return asset('assets/images/logo.png');
        }

        if ($variant && isset($defaults['default_' . $variant])) {
            return asset($defaults['default_' . $variant]);
        }

        if (isset($defaults['default'])) {
            return asset($defaults['default']);
        }

        return asset('assets/images/logo.png');
    }

    /**
     * Get logo styles for inline CSS.
     */
    public function getStyles(): string
    {
        $styles = [];

        if ($this->width) {
            $styles[] = "width: {$this->width}px";
        }

        if ($this->height) {
            $styles[] = "height: {$this->height}px";
        }

        if ($this->alignment) {
            $styles[] = "display: block; margin: {$this->getAlignmentMargin()}";
        }

        $customStyles = trim($this->custom_css ?? '');
        if ($customStyles) {
            $styles[] = $customStyles;
        }

        return implode('; ', $styles);
    }

    /**
     * Get margin based on alignment.
     */
    protected function getAlignmentMargin(): string
    {
        return match ($this->alignment) {
            'center' => '0 auto',
            'right' => '0 0 0 auto',
            default => '0',
        };
    }

    /**
     * Get all active logo settings.
     */
    public static function getAllActive(): array
    {
        return static::where('is_active', true)->get()->keyBy('logo_type')->toArray();
    }

    /**
     * Reset logo to default.
     */
    public function resetToDefault(): bool
    {
        $this->image_path = null;
        $this->image_url = null;
        $this->width = null;
        $this->height = null;
        $this->alignment = 'left';
        $this->position = 'default';
        $this->custom_css = null;

        return $this->save();
    }
}
