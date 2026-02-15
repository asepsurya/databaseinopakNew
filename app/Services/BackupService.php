<?php

namespace App\Services;

use App\Models\BackupHistory;
use App\Models\BackupSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class BackupService
{
    private BackupSetting $settings;
    private string $backupPath;
    private string $timezone;

    public function __construct()
    {
        $this->settings = BackupSetting::getSettings();
        $this->backupPath = $this->settings->backup_path;
        $this->timezone = config('app.timezone', 'Asia/Jakarta');
    }

    /**
     * Get all database tables
     */
    public function getTables(): array
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $tableNames[] = $tableName;
        }

        return $tableNames;
    }

    /**
     * Get table row counts for estimation
     */
    public function getTableRowCounts(): array
    {
        $tables = $this->getTables();
        $counts = [];

        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $counts[$table] = $count;
            } catch (Exception $e) {
                $counts[$table] = 0;
            }
        }

        return $counts;
    }

    /**
     * Estimate backup size for given tables
     */
    public function estimateBackupSize(?array $tables = null): array
    {
        $tables = $tables ?? $this->getTables();

        $totalSize = 0;
        $tableEstimates = [];

        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                // Rough estimate: ~500 bytes per row average
                $estimatedSize = $count * 500;
                $tableEstimates[$table] = [
                    'rows' => $count,
                    'estimated_size' => $estimatedSize,
                    'estimated_size_human' => $this->formatBytes($estimatedSize),
                ];
                $totalSize += $estimatedSize;
            } catch (Exception $e) {
                $tableEstimates[$table] = [
                    'rows' => 0,
                    'estimated_size' => 0,
                    'estimated_size_human' => '0 B',
                ];
            }
        }

        return [
            'tables' => $tableEstimates,
            'total_estimated_size' => $totalSize,
            'total_estimated_size_human' => $this->formatBytes($totalSize),
        ];
    }

    /**
     * Create full database backup
     */
    public function createFullBackup(?int $userId = null, bool $isScheduled = false): BackupHistory
    {
        $triggeredBy = $isScheduled ? 'scheduled' : 'manual';

        // Create backup history record
        $backupHistory = BackupHistory::create([
            'filename' => '',
            'path' => $this->backupPath,
            'format' => 'sql',
            'type' => 'full',
            'tables_included' => $this->getTables(),
            'file_size' => 0,
            'file_size_human' => '0 B',
            'is_encrypted' => $this->settings->encryption_enabled,
            'status' => 'in_progress',
            'started_at' => now(),
            'triggered_by' => $triggeredBy,
            'user_id' => $userId,
        ]);

        try {
            // Ensure backup directory exists
            $this->ensureBackupDirectory();

            // Generate filename
            $filename = $this->generateFilename('full');

            // Create SQL dump
            $sqlContent = $this->createSqlDump();

            // Encrypt if enabled
            if ($this->settings->encryption_enabled) {
                $sqlContent = $this->encryptContent($sqlContent);
                $filename .= '.enc';
            }

            // Save file
            $filePath = $this->backupPath . '/' . $filename;
            Storage::put($filePath, $sqlContent);

            // Get file size
            $fileSize = Storage::size($filePath);

            // Update backup history
            $backupHistory->update([
                'filename' => $filename,
                'file_size' => $fileSize,
                'file_size_human' => $this->formatBytes($fileSize),
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Log activity
            \Log::info('Database backup completed successfully', [
                'filename' => $filename,
                'size' => $this->formatBytes($fileSize),
                'type' => 'full',
                'triggered_by' => $triggeredBy,
                'user_id' => $userId,
            ]);

            // Send notification
            $this->sendBackupNotification($backupHistory, true);

            // Auto delete old backups if enabled
            if ($this->settings->auto_delete_old) {
                $this->deleteOldBackups();
            }

            return $backupHistory;

        } catch (Exception $e) {
            $backupHistory->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            // Log failure
            \Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
            ]);

            // Send failure notification
            $this->sendBackupNotification($backupHistory, false);

            throw $e;
        }
    }

    /**
     * Create per-table backup
     */
    public function createPerTableBackup(array $tables, ?int $userId = null, bool $isScheduled = false): BackupHistory
    {
        $triggeredBy = $isScheduled ? 'scheduled' : 'manual';

        // Validate tables
        $validTables = $this->getTables();
        $selectedTables = array_intersect($tables, $validTables);

        if (empty($selectedTables)) {
            throw new Exception('No valid tables selected for backup');
        }

        // Create backup history record
        $backupHistory = BackupHistory::create([
            'filename' => '',
            'path' => $this->backupPath,
            'format' => 'sql',
            'type' => 'per_table',
            'tables_included' => array_values($selectedTables),
            'file_size' => 0,
            'file_size_human' => '0 B',
            'is_encrypted' => $this->settings->encryption_enabled,
            'status' => 'in_progress',
            'started_at' => now(),
            'triggered_by' => $triggeredBy,
            'user_id' => $userId,
        ]);

        try {
            // Ensure backup directory exists
            $this->ensureBackupDirectory();

            // Generate filename
            $filename = $this->generateFilename('partial');

            // Create SQL dump for selected tables
            $sqlContent = $this->createSqlDump($selectedTables);

            // Encrypt if enabled
            if ($this->settings->encryption_enabled) {
                $sqlContent = $this->encryptContent($sqlContent);
                $filename .= '.enc';
            }

            // Save file
            $filePath = $this->backupPath . '/' . $filename;
            Storage::put($filePath, $sqlContent);

            // Get file size
            $fileSize = Storage::size($filePath);

            // Update backup history
            $backupHistory->update([
                'filename' => $filename,
                'file_size' => $fileSize,
                'file_size_human' => $this->formatBytes($fileSize),
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Log activity
            \Log::info('Per-table backup completed successfully', [
                'filename' => $filename,
                'size' => $this->formatBytes($fileSize),
                'tables' => array_values($selectedTables),
                'type' => 'per_table',
                'triggered_by' => $triggeredBy,
                'user_id' => $userId,
            ]);

            // Send notification
            $this->sendBackupNotification($backupHistory, true);

            return $backupHistory;

        } catch (Exception $e) {
            $backupHistory->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            // Log failure
            \Log::error('Per-table backup failed', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
            ]);

            // Send failure notification
            $this->sendBackupNotification($backupHistory, false);

            throw $e;
        }
    }

    /**
     * Create CSV export for specific tables
     */
    public function createCsvBackup(array $tables, ?int $userId = null): BackupHistory
    {
        // Validate tables
        $validTables = $this->getTables();
        $selectedTables = array_intersect($tables, $validTables);

        if (empty($selectedTables)) {
            throw new Exception('No valid tables selected for backup');
        }

        // Create backup history record
        $backupHistory = BackupHistory::create([
            'filename' => '',
            'path' => $this->backupPath,
            'format' => 'csv',
            'type' => 'per_table',
            'tables_included' => array_values($selectedTables),
            'file_size' => 0,
            'file_size_human' => '0 B',
            'is_encrypted' => $this->settings->encryption_enabled,
            'status' => 'in_progress',
            'started_at' => now(),
            'triggered_by' => 'manual',
            'user_id' => $userId,
        ]);

        try {
            // Ensure backup directory exists
            $this->ensureBackupDirectory();

            // Generate filename
            $filename = $this->generateFilename('csv');

            // Create CSV content
            $csvContent = $this->createCsvContent($selectedTables);

            // Encrypt if enabled
            if ($this->settings->encryption_enabled) {
                $csvContent = $this->encryptContent($csvContent);
                $filename .= '.enc';
            }

            // Save file
            $filePath = $this->backupPath . '/' . $filename;
            Storage::put($filePath, $csvContent);

            // Get file size
            $fileSize = Storage::size($filePath);

            // Update backup history
            $backupHistory->update([
                'filename' => $filename,
                'file_size' => $fileSize,
                'file_size_human' => $this->formatBytes($fileSize),
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            return $backupHistory;

        } catch (Exception $e) {
            $backupHistory->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            throw $e;
        }
    }

    /**
     * Create SQL dump of database
     */
    private function createSqlDump(?array $tables = null): string
    {
        $tables = $tables ?? $this->getTables();
        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: " . config('database.connections.mysql.database') . "\n\n";

        foreach ($tables as $table) {
            $sql .= $this->getTableCreateStatement($table);
            $sql .= $this->getTableData($table);
            $sql .= "\n";
        }

        return $sql;
    }

    /**
     * Get CREATE TABLE statement
     */
    private function getTableCreateStatement(string $table): string
    {
        $createStatement = DB::select("SHOW CREATE TABLE `$table`")[0];
        $sql = "\n\n-- Table: $table\n";
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $createStatement->{'Create Table'} . ";\n";

        return $sql;
    }

    /**
     * Get table data as INSERT statements
     */
    private function getTableData(string $table): string
    {
        $sql = '';
        $rows = DB::table($table)->get();

        if ($rows->isEmpty()) {
            return $sql;
        }

        $sql .= "\n-- Data for table: $table\n";

        foreach ($rows as $row) {
            $values = [];
            foreach ($row as $value) {
                if (is_null($value)) {
                    $values[] = 'NULL';
                } else {
                    $values[] = "'" . addslashes($value) . "'";
                }
            }

            $columns = implode('`, `', array_keys((array)$row));
            $values = implode(', ', $values);
            $sql .= "INSERT INTO `$table` (`$columns`) VALUES ($values);\n";
        }

        return $sql;
    }

    /**
     * Create CSV content for tables
     */
    private function createCsvContent(array $tables): string
    {
        $csv = '';

        foreach ($tables as $table) {
            $rows = DB::table($table)->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $csv .= "\n-- Table: $table\n";

            // Header row
            $headers = array_keys((array)$rows->first());
            $csv .= implode(',', $headers) . "\n";

            // Data rows
            foreach ($rows as $row) {
                $values = array_map(function($value) {
                    if (is_null($value)) {
                        return '';
                    }
                    // Escape quotes and wrap in quotes if contains comma or quote
                    $value = str_replace('"', '""', $value);
                    if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
                        return '"' . $value . '"';
                    }
                    return $value;
                }, array_values((array)$row));

                $csv .= implode(',', $values) . "\n";
            }

            $csv .= "\n";
        }

        return $csv;
    }

    /**
     * Encrypt content using AES-256
     */
    private function encryptContent(string $content): string
    {
        $password = $this->settings->encryption_password ?? config('app.key');

        // Generate a random IV
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        // Encrypt the content
        $encrypted = openssl_encrypt($content, 'aes-256-cbc', $password, 0, $iv);

        // Combine IV and encrypted data
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt content
     */
    public function decryptContent(string $encryptedContent): string
    {
        $password = $this->settings->encryption_password ?? config('app.key');

        // Decode from base64
        $data = base64_decode($encryptedContent);

        // Extract IV and encrypted data
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        // Decrypt
        return openssl_decrypt($encrypted, 'aes-256-cbc', $password, 0, $iv);
    }

    /**
     * Ensure backup directory exists
     */
    private function ensureBackupDirectory(): void
    {
        if (!Storage::exists($this->backupPath)) {
            Storage::makeDirectory($this->backupPath);
        }
    }

    /**
     * Generate unique filename
     */
    private function generateFilename(string $type): string
    {
        $prefix = match($type) {
            'full' => 'full_backup',
            'partial' => 'partial_backup',
            'csv' => 'csv_backup',
            default => 'backup',
        };

        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);

        return "{$prefix}_{$timestamp}_{$random}.sql";
    }

    /**
     * Delete old backups based on retention policy
     */
    public function deleteOldBackups(): int
    {
        $deletedCount = 0;

        $oldBackups = BackupHistory::completed()
            ->where('created_at', '<', now()->subDays($this->settings->retention_days))
            ->get();

        foreach ($oldBackups as $backup) {
            try {
                // Delete file from storage
                $filePath = $this->backupPath . '/' . $backup->filename;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }

                // Delete record
                $backup->delete();
                $deletedCount++;
            } catch (Exception $e) {
                // Log error but continue
                \Log::error("Failed to delete old backup: " . $backup->filename);
            }
        }

        return $deletedCount;
    }

    /**
     * Delete specific backup
     */
    public function deleteBackup(int $backupId): bool
    {
        $backup = BackupHistory::findOrFail($backupId);

        // Delete file
        $filePath = $this->backupPath . '/' . $backup->filename;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // Delete record
        $backup->delete();

        return true;
    }

    /**
     * Get backup file path for download
     */
    public function getBackupFilePath(int $backupId): ?string
    {
        $backup = BackupHistory::findOrFail($backupId);

        if (!$backup->fileExists()) {
            return null;
        }

        return $backup->getFullPath();
    }

    /**
     * Get backup history with filters
     */
    public function getBackupHistory(?string $status = null, ?string $type = null, int $perPage = 15)
    {
        $query = BackupHistory::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('type', $type);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get backup settings
     */
    public function getSettings(): BackupSetting
    {
        return $this->settings;
    }

    /**
     * Update backup settings
     */
    public function updateSettings(array $data): BackupSetting
    {
        $this->settings->update($data);
        $this->settings->refresh();

        return $this->settings;
    }

    /**
     * Send backup notification
     */
    private function sendBackupNotification(BackupHistory $backup, bool $success): void
    {
        try {
            $title = $success ? 'Backup Berhasil' : 'Backup Gagal';
            $message = $success
                ? "Backup database telah selesai dibuat: {$backup->filename} ({$backup->file_size_human})"
                : "Backup database gagal: {$backup->error_message}";

            // Create notification for all admins
            $admins = \App\Models\User::where('is_admin', true)->get();

            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => $success ? 'success' : 'error',
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            // Ignore notification errors - backup already completed
            \Log::warning('Failed to send backup notification: ' . $e->getMessage());
        }
    }

    /**
     * Format bytes to human readable
     */
    public function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Run scheduled backup
     */
    public function runScheduledBackup(): void
    {
        if (!$this->settings->isAutoBackupEnabled()) {
            return;
        }

        $now = now();
        $shouldBackup = false;

        if ($this->settings->frequency === 'daily') {
            $backupTime = $this->settings->daily_time;

            // Parse the time string (format: HH:MM:SS)
            $timeParts = explode(':', $backupTime);
            $hour = (int)$timeParts[0];
            $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
            $second = isset($timeParts[2]) ? (int)$timeParts[2] : 0;

            // Create a datetime for the backup time
            $backupDateTime = $now->copy()->setTime($hour, $minute, $second);

            // If scheduled time has passed today (within last hour) and not yet run
            if ($now->gte($backupDateTime) && $now->lt($backupDateTime->copy()->addHour())) {
                $shouldBackup = true;
            }
        } elseif ($this->settings->frequency === 'monthly') {
            $backupTime = $this->settings->monthly_time;
            $backupDay = $this->settings->monthly_day;
            $currentDay = $now->day;

            // Parse the time string
            $timeParts = explode(':', $backupTime);
            $hour = (int)$timeParts[0];
            $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
            $second = isset($timeParts[2]) ? (int)$timeParts[2] : 0;

            $backupDateTime = $now->copy()->setTime($hour, $minute, $second);

            if ($currentDay == $backupDay && $now->gte($backupDateTime) && $now->lt($backupDateTime->copy()->addHour())) {
                $shouldBackup = true;
            }
        }

        if ($shouldBackup) {
            // Check if already backed up today
            $lastBackup = BackupHistory::completed()
                ->scheduled()
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$lastBackup || $lastBackup->created_at->lt($now->startOfDay())) {
                $this->createFullBackup(null, true);
            }
        }
    }
}
