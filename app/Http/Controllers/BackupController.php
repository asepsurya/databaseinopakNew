<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Exception;

class BackupController extends Controller
{
    private BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display backup management page
     */
    public function index()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }

        $settings = $this->backupService->getSettings();
        $tables = $this->backupService->getTables();
        $tableCounts = $this->backupService->getTableRowCounts();
        $backupHistory = $this->backupService->getBackupHistory(null, null, 10);

        return view('pages.backup.index', compact(
            'settings',
            'tables',
            'tableCounts',
            'backupHistory'
        ));
    }

    /**
     * Get backup settings (API)
     */
    public function getSettings(): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $settings = $this->backupService->getSettings();

            return response()->json([
                'success' => true,
                'data' => $settings,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update backup settings (API)
     */
    public function updateSettings(Request $request): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            // Get checkbox values - they may not be present if unchecked
            $autoBackupEnabled = $request->has('auto_backup_enabled')
                ? filter_var($request->auto_backup_enabled, FILTER_VALIDATE_BOOLEAN)
                : false;
            $encryptionEnabled = $request->has('encryption_enabled')
                ? filter_var($request->encryption_enabled, FILTER_VALIDATE_BOOLEAN)
                : false;
            $autoDeleteOld = $request->has('auto_delete_old')
                ? filter_var($request->auto_delete_old, FILTER_VALIDATE_BOOLEAN)
                : false;

            $validated = $request->validate([
                'frequency' => 'nullable|in:daily,monthly',
                'daily_time' => 'nullable|date_format:H:i',
                'monthly_day' => 'nullable|integer|min:1|max:31',
                'monthly_time' => 'nullable|date_format:H:i',
                'backup_path' => 'nullable|string|max:255',
                'encryption_password' => 'nullable|string|min:8',
                'retention_days' => 'nullable|integer|min:1|max:365',
            ]);

            // Add checkbox values to validated data
            $validated['auto_backup_enabled'] = $autoBackupEnabled;
            $validated['encryption_enabled'] = $encryptionEnabled;
            $validated['auto_delete_old'] = $autoDeleteOld;

            // Handle password - only update if provided
            if (empty($validated['encryption_password'])) {
                unset($validated['encryption_password']);
            }

            $settings = $this->backupService->updateSettings($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan backup berhasil diperbarui',
                'data' => $settings,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle auto backup status (API)
     */
    public function toggleAutoBackup(Request $request): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $enabled = $request->boolean('enabled');

            $settings = $this->backupService->updateSettings([
                'auto_backup_enabled' => $enabled,
            ]);

            return response()->json([
                'success' => true,
                'message' => $enabled ? 'Auto backup diaktifkan' : 'Auto backup dinonaktifkan',
                'data' => [
                    'auto_backup_enabled' => $settings->auto_backup_enabled,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all database tables (API)
     */
    public function getTables(): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $tables = $this->backupService->getTables();
            $tableCounts = $this->backupService->getTableRowCounts();
            $estimates = $this->backupService->estimateBackupSize();

            return response()->json([
                'success' => true,
                'data' => [
                    'tables' => $tables,
                    'table_counts' => $tableCounts,
                    'estimates' => $estimates,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Estimate backup size (API)
     */
    public function estimateBackup(Request $request): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $tables = $request->input('tables', null);

            if ($tables && is_string($tables)) {
                $tables = explode(',', $tables);
            }

            $estimates = $this->backupService->estimateBackupSize($tables);

            return response()->json([
                'success' => true,
                'data' => $estimates,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create full database backup (API)
     */
    public function createFullBackup(): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $userId = Auth::id();

            // Run backup directly
            $backup = $this->backupService->createFullBackup($userId);

            return response()->json([
                'success' => true,
                'message' => 'Backup database penuh berhasil dibuat.',
                'data' => [
                    'id' => $backup->id,
                    'filename' => $backup->filename,
                    'size' => $backup->file_size_human,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create per-table backup (API)
     */
    public function createPerTableBackup(Request $request): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'tables' => 'required|array|min:1',
                'tables.*' => 'string',
            ]);

            $userId = Auth::id();
            $tables = $validated['tables'];

            // Run backup directly
            $backup = $this->backupService->createPerTableBackup($tables, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Backup tabel berhasil dibuat.',
                'data' => [
                    'id' => $backup->id,
                    'filename' => $backup->filename,
                    'size' => $backup->file_size_human,
                    'tables_count' => count($tables),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create CSV backup (API)
     */
    public function createCsvBackup(Request $request): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'tables' => 'required|array|min:1',
                'tables.*' => 'string',
            ]);

            $userId = Auth::id();
            $tables = $validated['tables'];

            // Run backup directly
            $backup = $this->backupService->createCsvBackup($tables, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Export CSV berhasil dibuat.',
                'data' => [
                    'id' => $backup->id,
                    'filename' => $backup->filename,
                    'size' => $backup->file_size_human,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup(int $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }

        try {
            $backup = \App\Models\BackupHistory::findOrFail($id);

            if ($backup->status !== 'completed') {
                return redirect()->back()->with('error', 'Backup belum selesai');
            }

            if (!$backup->fileExists()) {
                return redirect()->back()->with('error', 'File backup tidak ditemukan: ' . $backup->getFullPath());
            }

            $filePath = $backup->path . '/' . $backup->filename;
            $filename = $backup->filename;
            $disk = config('filesystems.default', 'local');

            // Get file content using Storage facade
            $content = Storage::disk($disk)->get($filePath);

            // Decrypt if encrypted
            if ($backup->is_encrypted) {
                $decrypted = $this->backupService->decryptContent($content);

                return Response::make($decrypted, 200, [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . str_replace('.enc', '', $filename) . '"',
                ]);
            }

            return Response::make($content, 200, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mendownload backup: ' . $e->getMessage());
        }
    }

    /**
     * Delete backup
     */
    public function deleteBackup(Request $request): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|integer',
            ]);

            $this->backupService->deleteBackup($validated['id']);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dihapus',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get backup history (API)
     */
    public function getBackupHistory(Request $request): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $status = $request->input('status');
            $type = $request->input('type');
            $perPage = $request->input('per_page', 15);

            $history = $this->backupService->getBackupHistory($status, $type, $perPage);

            return response()->json([
                'success' => true,
                'data' => $history,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get backup status (for polling)
     */
    public function getBackupStatus(int $id): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $backup = \App\Models\BackupHistory::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $backup->id,
                    'status' => $backup->status,
                    'progress' => $this->getBackupProgress($backup),
                    'file_size_human' => $backup->file_size_human,
                    'error_message' => $backup->error_message,
                    'completed_at' => $backup->completed_at?->toIso8601String(),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate backup progress
     */
    private function getBackupProgress(\App\Models\BackupHistory $backup): int
    {
        return match($backup->status) {
            'completed' => 100,
            'failed' => 0,
            'in_progress' => 50,
            'pending' => 0,
            default => 0,
        };
    }

    /**
     * Run cleanup of old backups
     */
    public function cleanupOldBackups(): JsonResponse
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $deletedCount = $this->backupService->deleteOldBackups();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} backup lama",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
