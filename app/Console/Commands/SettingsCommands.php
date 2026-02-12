<?php

namespace App\Console\Commands;

use App\Services\SettingsService;
use Illuminate\Console\Command;

class SettingsCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:seed-defaults {--logo : Seed only logo settings} {--app : Seed only app settings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed default settings for the application';

    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        parent::__construct();
        $this->settingsService = $settingsService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $seedLogo = $this->option('logo');
        $seedApp = $this->option('app');

        // If no options specified, seed everything
        if (!$seedLogo && !$seedApp) {
            $seedLogo = true;
            $seedApp = true;
        }

        if ($seedLogo) {
            $this->info('Seeding logo settings...');
            $this->settingsService->seedDefaultLogos();
            $this->info('Logo settings seeded successfully!');
        }

        if ($seedApp) {
            $this->info('Seeding app settings...');
            // Seed default app settings
            \App\Models\AppSetting::set('registration_enabled', 'true', 'boolean', 'Enable or disable user registration');
            \App\Models\AppSetting::set('app_name', 'Database INOPAK', 'string', 'Application name');
            \App\Models\AppSetting::set('app_version', '1.0.0', 'string', 'Application version');
            $this->info('App settings seeded successfully!');
        }

        $this->info('All settings have been seeded successfully!');
        return Command::SUCCESS;
    }
}
