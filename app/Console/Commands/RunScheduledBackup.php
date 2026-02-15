<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class RunScheduledBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scheduled database backup based on settings';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $this->info('Checking for scheduled backup...');

        try {
            $settings = $backupService->getSettings();

            if (!$settings->isAutoBackupEnabled()) {
                $this->info('Auto backup is disabled. Skipping.');
                return Command::SUCCESS;
            }

            $this->info('Auto backup is enabled. Checking schedule...');

            $backupService->runScheduledBackup();

            $this->info('Scheduled backup check completed.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error running scheduled backup: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
