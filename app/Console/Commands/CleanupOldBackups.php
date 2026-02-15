<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class CleanupOldBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleanup {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old database backups based on retention settings';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $this->info('Starting backup cleanup...');

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Running in dry-run mode. No files will be deleted.');
        }

        try {
            $settings = $backupService->getSettings();

            if (!$settings->auto_delete_old) {
                $this->info('Auto delete old backups is disabled. Skipping.');
                return Command::SUCCESS;
            }

            if ($dryRun) {
                // Show what would be deleted
                $oldBackups = \App\Models\BackupHistory::completed()
                    ->where('created_at', '<', now()->subDays($settings->retention_days))
                    ->get();

                $this->info("Would delete {$oldBackups->count()} backup(s):");

                foreach ($oldBackups as $backup) {
                    $this->line("  - {$backup->filename} ({$backup->file_size_human}) - Created: {$backup->created_at->format('Y-m-d')}");
                }

                return Command::SUCCESS;
            }

            $deletedCount = $backupService->deleteOldBackups();

            $this->info("Successfully deleted {$deletedCount} old backup(s).");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error cleaning up backups: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
