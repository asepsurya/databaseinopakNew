<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\LogoSetting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed logo settings
        $logoDefaults = [
            'header' => [
                'name' => 'Header Logo',
                'is_active' => true,
                'alignment' => 'left',
                'position' => 'default',
            ],
            'footer' => [
                'name' => 'Footer Logo',
                'is_active' => true,
                'alignment' => 'center',
                'position' => 'default',
            ],
            'sidebar' => [
                'name' => 'Sidebar Logo',
                'is_active' => true,
                'alignment' => 'center',
                'position' => 'default',
            ],
            'login' => [
                'name' => 'Login Page Logo',
                'is_active' => true,
                'alignment' => 'center',
                'position' => 'default',
            ],
            'favicon' => [
                'name' => 'Favicon',
                'is_active' => true,
                'alignment' => 'center',
                'position' => 'default',
            ],
        ];

        foreach ($logoDefaults as $type => $config) {
            LogoSetting::updateOrCreate(
                ['logo_type' => $type],
                $config
            );
        }

        // Seed app settings
        $appSettings = [
            [
                'key' => 'registration_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable or disable user registration',
            ],
            [
                'key' => 'app_name',
                'value' => 'Database INOPAK',
                'type' => 'string',
                'description' => 'Application name displayed in browser title and header',
            ],
            [
                'key' => 'app_version',
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Current application version',
            ],
            [
                'key' => 'max_upload_size',
                'value' => '2048',
                'type' => 'integer',
                'description' => 'Maximum file upload size in KB',
            ],
            [
                'key' => 'allowed_image_types',
                'value' => json_encode(['png', 'jpg', 'jpeg', 'svg', 'ico', 'gif', 'webp']),
                'type' => 'json',
                'description' => 'Allowed image types for logo upload',
            ],
        ];

        foreach ($appSettings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
