<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\BackupSetting;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SettingsCommands::class,
        Commands\FixLogoUrls::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the backup command
        // We'll run it every hour and let the command decide if it should run based on settings
        $schedule->command('backup:run-scheduled')->hourly();

        // Also run cleanup command daily at 3 AM
        $schedule->command('backup:cleanup')->dailyAt('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // Require additional command files if any
        // $this->load(__DIR__.'/AdditionalCommands');
    }
}
