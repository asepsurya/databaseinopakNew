<?php

namespace App\Enums;

enum NotificationType: string
{
    // ==================== AUTHENTICATION ====================
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case LOGIN_FAILED = 'login_failed';
    case PASSWORD_RESET = 'password_reset';
    case PASSWORD_CHANGED = 'password_changed';
    case ACCOUNT_CREATED = 'account_created';
    case ACCOUNT_DELETED = 'account_deleted';
    case SESSION_EXPIRED = 'session_expired';

    // ==================== DATA OPERATIONS ====================
    case DATA_CREATED = 'data_created';
    case DATA_UPDATED = 'data_updated';
    case DATA_DELETED = 'data_deleted';
    case DATA_RESTORED = 'data_restored';
    case DATA_IMPORTED = 'data_imported';
    case DATA_EXPORTED = 'data_exported';
    case BULK_ACTION = 'bulk_action';

    // ==================== FORM SUBMISSIONS ====================
    case FORM_SUBMITTED = 'form_submitted';
    case FORM_APPROVED = 'form_approved';
    case FORM_REJECTED = 'form_rejected';
    case FORM_DRAFT_SAVED = 'form_draft_saved';

    // ==================== PROFILE ====================
    case PROFILE_UPDATED = 'profile_updated';
    case PROFILE_PHOTO_UPDATED = 'profile_photo_updated';
    case PROFILE_DELETED = 'profile_deleted';
    case PREFERENCES_UPDATED = 'preferences_updated';

    // ==================== PROJECT ====================
    case PROJECT_CREATED = 'project_created';
    case PROJECT_UPDATED = 'project_updated';
    case PROJECT_DELETED = 'project_deleted';
    case PROJECT_COMPLETED = 'project_completed';
    case PROJECT_ARCHIVED = 'project_archived';

    // ==================== Ikm (Industri Kecil Menengah) ====================
    case Ikm_CREATED = 'Ikm_created';
    case Ikm_UPDATED = 'Ikm_updated';
    case Ikm_DELETED = 'Ikm_deleted';
    case Ikm_APPROVED = 'Ikm_approved';
    case Ikm_REJECTED = 'Ikm_rejected';
    case Ikm_VERIFIED = 'Ikm_verified';

    // ==================== Cots (Commercial Off-The-Shelf) ====================
    case Cots_CREATED = 'Cots_created';
    case Cots_UPDATED = 'Cots_updated';
    case Cots_DELETED = 'Cots_deleted';
    case Cots_INSTALLED = 'Cots_installed';
    case Cots_REMOVED = 'Cots_removed';

    // ==================== BENCHMARK PRODUK ====================
    case BENCHMARK_CREATED = 'benchmark_created';
    case BENCHMARK_UPDATED = 'benchmark_updated';
    case BENCHMARK_DELETED = 'benchmark_deleted';
    case BENCHMARK_APPROVED = 'benchmark_approved';
    case BENCHMARK_REJECTED = 'benchmark_rejected';

    // ==================== DOKUMENTASI Cots ====================
    case DOKUMENTASI_CREATED = 'dokumentasi_created';
    case DOKUMENTASI_UPDATED = 'dokumentasi_updated';
    case DOKUMENTASI_DELETED = 'dokumentasi_deleted';
    case DOKUMENTASI_APPROVED = 'dokumentasi_approved';

    // ==================== PRODUK DESIGN ====================
    case PRODUK_DESIGN_CREATED = 'produk_design_created';
    case PRODUK_DESIGN_UPDATED = 'produk_design_updated';
    case PRODUK_DESIGN_DELETED = 'produk_design_deleted';
    case PRODUK_DESIGN_APPROVED = 'produk_design_approved';
    case PRODUK_DESIGN_REJECTED = 'produk_design_rejected';

    // ==================== EXPORT/IMPORT ====================
    case EXPORT_STARTED = 'export_started';
    case EXPORT_COMPLETED = 'export_completed';
    case EXPORT_FAILED = 'export_failed';
    case IMPORT_STARTED = 'import_started';
    case IMPORT_COMPLETED = 'import_completed';
    case IMPORT_FAILED = 'import_failed';

    // ==================== TRANSACTIONS ====================
    case TRANSACTION_CREATED = 'transaction_created';
    case TRANSACTION_COMPLETED = 'transaction_completed';
    case TRANSACTION_FAILED = 'transaction_failed';
    case TRANSACTION_CANCELLED = 'transaction_cancelled';
    case TRANSACTION_REFUNDED = 'transaction_refunded';

    // ==================== SYSTEM ====================
    case SYSTEM_ERROR = 'system_error';
    case SYSTEM_WARNING = 'system_warning';
    case SYSTEM_INFO = 'system_info';
    case BACKUP_STARTED = 'backup_started';
    case BACKUP_COMPLETED = 'backup_completed';
    case BACKUP_FAILED = 'backup_failed';
    case MAINTENANCE_STARTED = 'maintenance_started';
    case MAINTENANCE_COMPLETED = 'maintenance_completed';
    case MAINTENANCE_MODE = 'maintenance_mode';
    case SERVER_ERROR = 'server_error';
    case DATABASE_ERROR = 'database_error';

    // ==================== SECURITY ====================
    case SECURITY_ALERT = 'security_alert';
    case UNAUTHORIZED_ACCESS = 'unauthorized_access';
    case SUSPICIOUS_ACTIVITY = 'suspicious_activity';
    case PASSWORD_EXPIRED = 'password_expired';
    case TWO_FACTOR_ENABLED = 'two_factor_enabled';
    case TWO_FACTOR_DISABLED = 'two_factor_disabled';

    // ==================== USER ACTIVITY ====================
    case USER_LOGGED_IN = 'user_logged_in';
    case USER_LOGGED_OUT = 'user_logged_out';
    case USER_CREATED = 'user_created';
    case USER_UPDATED = 'user_updated';
    case USER_DELETED = 'user_deleted';
    case USER_ROLE_CHANGED = 'user_role_changed';
    case USER_PERMISSION_CHANGED = 'user_permission_changed';

    // ==================== SYNC & INTEGRATION ====================
    case SYNC_STARTED = 'sync_started';
    case SYNC_COMPLETED = 'sync_completed';
    case SYNC_FAILED = 'sync_failed';
    case API_CALL_SUCCESS = 'api_call_success';
    case API_CALL_FAILED = 'api_call_failed';

    // ==================== REPORT & ANALYTICS ====================
    case REPORT_GENERATED = 'report_generated';
    case REPORT_EXPORTED = 'report_exported';
    case REPORT_FAILED = 'report_failed';
    case ANALYTICS_UPDATED = 'analytics_updated';

    // ==================== HELP & SUPPORT ====================
    case TICKET_CREATED = 'ticket_created';
    case TICKET_UPDATED = 'ticket_updated';
    case TICKET_CLOSED = 'ticket_closed';
    case FEEDBACK_SUBMITTED = 'feedback_submitted';

    // ==================== GET ICON ====================
    public function getIcon(): string
    {
        return match($this) {
            // Authentication
            self::LOGIN, self::LOGOUT, self::USER_LOGGED_IN, self::USER_LOGGED_OUT => 'ti ti-login',
            self::LOGIN_FAILED, self::SESSION_EXPIRED, self::UNAUTHORIZED_ACCESS => 'ti ti-login-2',
            self::PASSWORD_RESET, self::PASSWORD_CHANGED, self::PASSWORD_EXPIRED => 'ti ti-key',
            self::SECURITY_ALERT, self::SUSPICIOUS_ACTIVITY => 'ti ti-shield-alert',

            // Data Operations
            self::DATA_CREATED, self::BENCHMARK_CREATED, self::DOKUMENTASI_CREATED,
            self::PRODUK_DESIGN_CREATED, self::USER_CREATED => 'ti ti-plus',
            self::DATA_UPDATED, self::BENCHMARK_UPDATED, self::DOKUMENTASI_UPDATED,
            self::PRODUK_DESIGN_UPDATED, self::USER_UPDATED, self::FORM_DRAFT_SAVED => 'ti ti-pencil',
            self::DATA_DELETED, self::BENCHMARK_DELETED, self::DOKUMENTASI_DELETED,
            self::PRODUK_DESIGN_DELETED, self::USER_DELETED, self::ACCOUNT_DELETED,
            self::Cots_REMOVED, self::PROFILE_DELETED => 'ti ti-trash',
            self::DATA_RESTORED => 'ti ti-recycle',
            self::DATA_IMPORTED, self::IMPORT_STARTED, self::IMPORT_COMPLETED => 'ti ti-upload',
            self::DATA_EXPORTED, self::EXPORT_STARTED, self::EXPORT_COMPLETED => 'ti ti-download',
            self::BULK_ACTION => 'ti ti-stack-2',

            // Form Submissions
            self::FORM_SUBMITTED, self::TICKET_CREATED, self::FEEDBACK_SUBMITTED => 'ti ti-file-description',
            self::FORM_APPROVED, self::BENCHMARK_APPROVED, self::DOKUMENTASI_APPROVED,
            self::PRODUK_DESIGN_APPROVED, self::Ikm_APPROVED, self::TICKET_CLOSED => 'ti ti-check',
            self::FORM_REJECTED, self::BENCHMARK_REJECTED, self::PRODUK_DESIGN_REJECTED,
            self::Ikm_REJECTED => 'ti ti-x',

            // Profile
            self::PROFILE_UPDATED, self::USER_ROLE_CHANGED, self::PREFERENCES_UPDATED => 'ti ti-user',
            self::PROFILE_PHOTO_UPDATED, self::USER_UPDATED => 'ti ti-user-circle',
            self::TWO_FACTOR_ENABLED, self::TWO_FACTOR_DISABLED => 'ti ti-shield',

            // Project
            self::PROJECT_CREATED, self::Ikm_CREATED, self::Cots_CREATED => 'ti ti-folder-plus',
            self::PROJECT_UPDATED, self::Ikm_UPDATED, self::Cots_UPDATED => 'ti ti-folder-edit',
            self::PROJECT_DELETED, self::Ikm_DELETED, self::Cots_DELETED => 'ti ti-folder-minus',
            self::PROJECT_COMPLETED => 'ti ti-folder-check',
            self::PROJECT_ARCHIVED => 'ti ti-folder-zip',

            // Ikm
            self::Ikm_VERIFIED => 'ti ti-badge-check',

            // Cots
            self::Cots_INSTALLED => 'ti ti-package',

            // Export/Import
            self::EXPORT_FAILED, self::IMPORT_FAILED, self::REPORT_FAILED => 'ti ti-exclamation-circle',
            self::EXPORT_STARTED, self::IMPORT_STARTED => 'ti ti-arrow-up-circle',
            self::EXPORT_COMPLETED, self::IMPORT_COMPLETED => 'ti ti-arrow-down-circle',

            // Transactions
            self::TRANSACTION_CREATED, self::REPORT_GENERATED, self::ANALYTICS_UPDATED => 'ti ti-receipt',
            self::TRANSACTION_COMPLETED, self::REPORT_EXPORTED => 'ti ti-check',
            self::TRANSACTION_FAILED, self::REPORT_FAILED => 'ti ti-alert-triangle',
            self::TRANSACTION_CANCELLED => 'ti ti-ban',
            self::TRANSACTION_REFUNDED => 'ti ti-cash-refund',

            // System
            self::SYSTEM_ERROR, self::SERVER_ERROR, self::DATABASE_ERROR => 'ti ti-error-404',
            self::SYSTEM_WARNING => 'ti ti-exclamation-triangle',
            self::SYSTEM_INFO => 'ti ti-info-circle',
            self::BACKUP_STARTED => 'ti ti-database-upload',
            self::BACKUP_COMPLETED => 'ti ti-database',
            self::BACKUP_FAILED => 'ti ti-database-off',
            self::MAINTENANCE_STARTED, self::MAINTENANCE_MODE => 'ti ti-tools',
            self::MAINTENANCE_COMPLETED => 'ti ti-tool',

            // Security
            self::ACCOUNT_CREATED, self::USER_CREATED => 'ti ti-user-plus',
            self::USER_PERMISSION_CHANGED => 'ti ti-keyboard',
            self::PASSWORD_EXPIRED => 'ti ti-lock',

            // Sync & Integration
            self::SYNC_STARTED => 'ti ti-refresh',
            self::SYNC_COMPLETED => 'ti ti-check',
            self::SYNC_FAILED => 'ti ti-refresh-alert',
            self::API_CALL_SUCCESS => 'ti ti-api',
            self::API_CALL_FAILED => 'ti ti-api-off',

            // Report & Analytics
            self::REPORT_GENERATED => 'ti ti-chart-bar',
            self::REPORT_EXPORTED => 'ti ti-file-export',

            // Help & Support
            self::TICKET_CREATED, self::TICKET_UPDATED => 'ti ti-ticket',
            self::FEEDBACK_SUBMITTED => 'ti ti-message-2',
        };
    }

    // ==================== GET COLOR ====================
    public function getColor(): string
    {
        return match($this) {
            // Success states - Green
            self::LOGIN, self::LOGOUT, self::USER_LOGGED_IN, self::USER_LOGGED_OUT,
            self::DATA_CREATED, self::FORM_APPROVED, self::FORM_SUBMITTED,
            self::TRANSACTION_COMPLETED, self::EXPORT_COMPLETED, self::IMPORT_COMPLETED,
            self::BACKUP_COMPLETED, self::PROJECT_CREATED, self::Ikm_CREATED, self::Cots_CREATED,
            self::BENCHMARK_CREATED, self::DOKUMENTASI_CREATED, self::PRODUK_DESIGN_CREATED,
            self::PROJECT_COMPLETED, self::Ikm_APPROVED, self::Ikm_VERIFIED,
            self::Cots_INSTALLED, self::BENCHMARK_APPROVED, self::DOKUMENTASI_APPROVED,
            self::PRODUK_DESIGN_APPROVED, self::TICKET_CLOSED, self::SYNC_COMPLETED,
            self::API_CALL_SUCCESS, self::REPORT_GENERATED, self::REPORT_EXPORTED,
            self::TRANSACTION_CREATED, self::ACCOUNT_CREATED, self::USER_CREATED,
            self::PROFILE_PHOTO_UPDATED, self::FORM_DRAFT_SAVED, self::BACKUP_STARTED,
            self::MAINTENANCE_COMPLETED, self::ANALYTICS_UPDATED, self::FEEDBACK_SUBMITTED => 'success',

            // Danger/Failure states - Red
            self::LOGIN_FAILED, self::SESSION_EXPIRED, self::DATA_DELETED,
            self::FORM_REJECTED, self::TRANSACTION_FAILED, self::IMPORT_FAILED,
            self::EXPORT_FAILED, self::SYSTEM_ERROR, self::SERVER_ERROR, self::DATABASE_ERROR,
            self::ACCOUNT_DELETED, self::USER_DELETED, self::PROJECT_DELETED,
            self::Ikm_DELETED, self::Cots_DELETED, self::BENCHMARK_DELETED,
            self::DOKUMENTASI_DELETED, self::PRODUK_DESIGN_DELETED,
            self::Ikm_REJECTED, self::BENCHMARK_REJECTED, self::PRODUK_DESIGN_REJECTED,
            self::TRANSACTION_CANCELLED, self::TRANSACTION_REFUNDED, self::REPORT_FAILED,
            self::UNAUTHORIZED_ACCESS, self::SUSPICIOUS_ACTIVITY, self::SECURITY_ALERT,
            self::SYNC_FAILED, self::API_CALL_FAILED, self::BACKUP_FAILED,
            self::Cots_REMOVED, self::MAINTENANCE_STARTED, self::PASSWORD_EXPIRED,
            self::PROFILE_DELETED => 'danger',

            // Warning states - Yellow/Orange
            self::PASSWORD_RESET, self::PASSWORD_CHANGED, self::DATA_UPDATED,
            self::FORM_DRAFT_SAVED, self::PROJECT_UPDATED, self::Ikm_UPDATED,
            self::Cots_UPDATED, self::BENCHMARK_UPDATED, self::DOKUMENTASI_UPDATED,
            self::PRODUK_DESIGN_UPDATED, self::USER_UPDATED, self::TRANSACTION_CREATED,
            self::SYSTEM_WARNING, self::MAINTENANCE_MODE, self::TICKET_UPDATED,
            self::USER_ROLE_CHANGED, self::USER_PERMISSION_CHANGED, self::PREFERENCES_UPDATED,
            self::PROFILE_UPDATED => 'warning',

            // Info states - Blue/Cyan
            self::DATA_IMPORTED, self::DATA_EXPORTED, self::BULK_ACTION,
            self::SYNC_STARTED, self::IMPORT_STARTED, self::EXPORT_STARTED,
            self::SYSTEM_INFO, self::REPORT_GENERATED => 'info',

            // Primary states - Indigo/Blue
            self::TICKET_CREATED, self::FEEDBACK_SUBMITTED, self::DATA_RESTORED,
            self::MAINTENANCE_COMPLETED, self::ANALYTICS_UPDATED, self::BACKUP_STARTED,
            self::TWO_FACTOR_ENABLED, self::TWO_FACTOR_DISABLED => 'primary',

            // Secondary states - Gray
            self::TRANSACTION_CREATED, self::REPORT_EXPORTED, self::API_CALL_SUCCESS,
            self::USER_LOGGED_IN, self::USER_LOGGED_OUT => 'secondary',
        };
    }

    // ==================== GET TITLE ====================
    public function getTitle(): string
    {
        return match($this) {
            // Authentication
            self::LOGIN => 'Login Berhasil',
            self::LOGOUT => 'Logout',
            self::USER_LOGGED_IN => 'Pengguna Login',
            self::USER_LOGGED_OUT => 'Pengguna Logout',
            self::LOGIN_FAILED => 'Login Gagal',
            self::SESSION_EXPIRED => 'Sesi Berakhir',
            self::PASSWORD_RESET => 'Reset Password',
            self::PASSWORD_CHANGED => 'Password Diubah',
            self::PASSWORD_EXPIRED => 'Password Kedaluwarsa',
            self::ACCOUNT_CREATED => 'Akun Baru Dibuat',
            self::ACCOUNT_DELETED => 'Akun Dihapus',
            self::SECURITY_ALERT => 'Peringatan Keamanan',
            self::UNAUTHORIZED_ACCESS => 'Akses Tidak Sah',
            self::SUSPICIOUS_ACTIVITY => 'Aktivitas Mencurigakan',

            // Data Operations
            self::DATA_CREATED => 'Data Baru',
            self::DATA_UPDATED => 'Data Diperbarui',
            self::DATA_DELETED => 'Data Dihapus',
            self::DATA_RESTORED => 'Data Dipulihkan',
            self::DATA_IMPORTED => 'Data Diimport',
            self::DATA_EXPORTED => 'Data Diexport',
            self::BULK_ACTION => 'Aksi Massal',

            // Form Submissions
            self::FORM_SUBMITTED => 'Form Dikirim',
            self::FORM_DRAFT_SAVED => 'Draft Disimpan',
            self::FORM_APPROVED => 'Form Disetujui',
            self::FORM_REJECTED => 'Form Ditolak',

            // Profile
            self::PROFILE_UPDATED => 'Profil Diperbarui',
            self::PROFILE_PHOTO_UPDATED => 'Foto Profil Diubah',
            self::PROFILE_DELETED => 'Profil Dihapus',
            self::PREFERENCES_UPDATED => 'Preferensi Diperbarui',
            self::USER_ROLE_CHANGED => 'Peran Diubah',
            self::USER_PERMISSION_CHANGED => 'Izin Diubah',
            self::TWO_FACTOR_ENABLED => '2FA Diaktifkan',
            self::TWO_FACTOR_DISABLED => '2FA Dinonaktifkan',

            // Project
            self::PROJECT_CREATED => 'Proyek Baru',
            self::PROJECT_UPDATED => 'Proyek Diperbarui',
            self::PROJECT_DELETED => 'Proyek Dihapus',
            self::PROJECT_COMPLETED => 'Proyek Selesai',
            self::PROJECT_ARCHIVED => 'Proyek Diarsipkan',

            // Ikm
            self::Ikm_CREATED => 'Data Ikm Baru',
            self::Ikm_UPDATED => 'Data Ikm Diperbarui',
            self::Ikm_DELETED => 'Data Ikm Dihapus',
            self::Ikm_APPROVED => 'Ikm Disetujui',
            self::Ikm_REJECTED => 'Ikm Ditolak',
            self::Ikm_VERIFIED => 'Ikm Terverifikasi',

            // Cots
            self::Cots_CREATED => 'Data Cots Baru',
            self::Cots_UPDATED => 'Data Cots Diperbarui',
            self::Cots_DELETED => 'Data Cots Dihapus',
            self::Cots_INSTALLED => 'Cots Diinstal',
            self::Cots_REMOVED => 'Cots Dihapus',

            // Benchmark
            self::BENCHMARK_CREATED => 'Benchmark Baru',
            self::BENCHMARK_UPDATED => 'Benchmark Diperbarui',
            self::BENCHMARK_DELETED => 'Benchmark Dihapus',
            self::BENCHMARK_APPROVED => 'Benchmark Disetujui',
            self::BENCHMARK_REJECTED => 'Benchmark Ditolak',

            // Dokumentasi
            self::DOKUMENTASI_CREATED => 'Dokumentasi Baru',
            self::DOKUMENTASI_UPDATED => 'Dokumentasi Diperbarui',
            self::DOKUMENTASI_DELETED => 'Dokumentasi Dihapus',
            self::DOKUMENTASI_APPROVED => 'Dokumentasi Disetujui',

            // Produk Design
            self::PRODUK_DESIGN_CREATED => 'Desain Produk Baru',
            self::PRODUK_DESIGN_UPDATED => 'Desain Produk Diperbarui',
            self::PRODUK_DESIGN_DELETED => 'Desain Produk Dihapus',
            self::PRODUK_DESIGN_APPROVED => 'Desain Produk Disetujui',
            self::PRODUK_DESIGN_REJECTED => 'Desain Produk Ditolak',

            // Export/Import
            self::EXPORT_STARTED => 'Export Dimulai',
            self::EXPORT_COMPLETED => 'Export Selesai',
            self::EXPORT_FAILED => 'Export Gagal',
            self::IMPORT_STARTED => 'Import Dimulai',
            self::IMPORT_COMPLETED => 'Import Selesai',
            self::IMPORT_FAILED => 'Import Gagal',

            // Transactions
            self::TRANSACTION_CREATED => 'Transaksi Baru',
            self::TRANSACTION_COMPLETED => 'Transaksi Selesai',
            self::TRANSACTION_FAILED => 'Transaksi Gagal',
            self::TRANSACTION_CANCELLED => 'Transaksi Dibatalkan',
            self::TRANSACTION_REFUNDED => 'Transaksi Dikembalikan',

            // System
            self::SYSTEM_ERROR => 'Kesalahan Sistem',
            self::SERVER_ERROR => 'Kesalahan Server',
            self::DATABASE_ERROR => 'Kesalahan Database',
            self::SYSTEM_WARNING => 'Peringatan Sistem',
            self::SYSTEM_INFO => 'Informasi Sistem',
            self::BACKUP_STARTED => 'Backup Dimulai',
            self::BACKUP_COMPLETED => 'Backup Selesai',
            self::BACKUP_FAILED => 'Backup Gagal',
            self::MAINTENANCE_STARTED => 'Pemeliharaan Dimulai',
            self::MAINTENANCE_COMPLETED => 'Pemeliharaan Selesai',
            self::MAINTENANCE_MODE => 'Mode Pemeliharaan',

            // User Activity
            self::USER_CREATED => 'Pengguna Baru',
            self::USER_UPDATED => 'Pengguna Diperbarui',
            self::USER_DELETED => 'Pengguna Dihapus',

            // Sync & Integration
            self::SYNC_STARTED => 'Sinkronisasi Dimulai',
            self::SYNC_COMPLETED => 'Sinkronisasi Selesai',
            self::SYNC_FAILED => 'Sinkronisasi Gagal',
            self::API_CALL_SUCCESS => 'API Berhasil',
            self::API_CALL_FAILED => 'API Gagal',

            // Report & Analytics
            self::REPORT_GENERATED => 'Laporan Dibuat',
            self::REPORT_EXPORTED => 'Laporan Diexport',
            self::REPORT_FAILED => 'Laporan Gagal',
            self::ANALYTICS_UPDATED => 'Analitik Diperbarui',

            // Help & Support
            self::TICKET_CREATED => 'Tiket Baru',
            self::TICKET_UPDATED => 'Tiket Diperbarui',
            self::TICKET_CLOSED => 'Tiket Ditutup',
            self::FEEDBACK_SUBMITTED => 'Masukan Dikirim',
        };
    }

    // ==================== GET CATEGORY ====================
    public function getCategory(): string
    {
        return match($this) {
            self::LOGIN, self::LOGOUT, self::LOGIN_FAILED, self::SESSION_EXPIRED,
            self::PASSWORD_RESET, self::PASSWORD_CHANGED, self::PASSWORD_EXPIRED,
            self::ACCOUNT_CREATED, self::ACCOUNT_DELETED, self::SECURITY_ALERT,
            self::UNAUTHORIZED_ACCESS, self::SUSPICIOUS_ACTIVITY => 'authentication',

            self::DATA_CREATED, self::DATA_UPDATED, self::DATA_DELETED,
            self::DATA_RESTORED, self::DATA_IMPORTED, self::DATA_EXPORTED,
            self::BULK_ACTION => 'data_operation',

            self::FORM_SUBMITTED, self::FORM_APPROVED, self::FORM_REJECTED,
            self::FORM_DRAFT_SAVED => 'form',

            self::PROFILE_UPDATED, self::PROFILE_PHOTO_UPDATED, self::PROFILE_DELETED,
            self::PREFERENCES_UPDATED, self::USER_ROLE_CHANGED, self::USER_PERMISSION_CHANGED,
            self::TWO_FACTOR_ENABLED, self::TWO_FACTOR_DISABLED => 'profile',

            self::PROJECT_CREATED, self::PROJECT_UPDATED, self::PROJECT_DELETED,
            self::PROJECT_COMPLETED, self::PROJECT_ARCHIVED => 'project',

            self::Ikm_CREATED, self::Ikm_UPDATED, self::Ikm_DELETED,
            self::Ikm_APPROVED, self::Ikm_REJECTED, self::Ikm_VERIFIED => 'Ikm',

            self::Cots_CREATED, self::Cots_UPDATED, self::Cots_DELETED,
            self::Cots_INSTALLED, self::Cots_REMOVED => 'Cots',

            self::BENCHMARK_CREATED, self::BENCHMARK_UPDATED, self::BENCHMARK_DELETED,
            self::BENCHMARK_APPROVED, self::BENCHMARK_REJECTED => 'benchmark',

            self::DOKUMENTASI_CREATED, self::DOKUMENTASI_UPDATED, self::DOKUMENTASI_DELETED,
            self::DOKUMENTASI_APPROVED => 'dokumentasi',

            self::PRODUK_DESIGN_CREATED, self::PRODUK_DESIGN_UPDATED, self::PRODUK_DESIGN_DELETED,
            self::PRODUK_DESIGN_APPROVED, self::PRODUK_DESIGN_REJECTED => 'produk_design',

            self::EXPORT_STARTED, self::EXPORT_COMPLETED, self::EXPORT_FAILED,
            self::IMPORT_STARTED, self::IMPORT_COMPLETED, self::IMPORT_FAILED => 'import_export',

            self::TRANSACTION_CREATED, self::TRANSACTION_COMPLETED,
            self::TRANSACTION_FAILED, self::TRANSACTION_CANCELLED,
            self::TRANSACTION_REFUNDED => 'transaction',

            self::SYSTEM_ERROR, self::SYSTEM_WARNING, self::SYSTEM_INFO,
            self::BACKUP_STARTED, self::BACKUP_COMPLETED, self::BACKUP_FAILED,
            self::MAINTENANCE_STARTED, self::MAINTENANCE_COMPLETED,
            self::MAINTENANCE_MODE, self::SERVER_ERROR, self::DATABASE_ERROR => 'system',

            self::USER_LOGGED_IN, self::USER_LOGGED_OUT, self::USER_CREATED,
            self::USER_UPDATED, self::USER_DELETED => 'user_activity',

            self::SYNC_STARTED, self::SYNC_COMPLETED, self::SYNC_FAILED,
            self::API_CALL_SUCCESS, self::API_CALL_FAILED => 'integration',

            self::REPORT_GENERATED, self::REPORT_EXPORTED, self::REPORT_FAILED,
            self::ANALYTICS_UPDATED => 'report',

            self::TICKET_CREATED, self::TICKET_UPDATED, self::TICKET_CLOSED,
            self::FEEDBACK_SUBMITTED => 'support',
        };
    }

    // ==================== IS SUCCESS ====================
    public function isSuccess(): bool
    {
        return in_array($this, [
            self::LOGIN,
            self::USER_LOGGED_IN,
            self::DATA_CREATED,
            self::DATA_UPDATED,
            self::DATA_RESTORED,
            self::DATA_IMPORTED,
            self::FORM_APPROVED,
            self::FORM_SUBMITTED,
            self::FORM_DRAFT_SAVED,
            self::PROFILE_UPDATED,
            self::PROFILE_PHOTO_UPDATED,
            self::TRANSACTION_CREATED,
            self::TRANSACTION_COMPLETED,
            self::PROJECT_CREATED,
            self::PROJECT_UPDATED,
            self::PROJECT_COMPLETED,
            self::Ikm_CREATED,
            self::Ikm_UPDATED,
            self::Ikm_APPROVED,
            self::Ikm_VERIFIED,
            self::Cots_CREATED,
            self::Cots_UPDATED,
            self::Cots_INSTALLED,
            self::BENCHMARK_CREATED,
            self::BENCHMARK_UPDATED,
            self::BENCHMARK_APPROVED,
            self::DOKUMENTASI_CREATED,
            self::DOKUMENTASI_UPDATED,
            self::DOKUMENTASI_APPROVED,
            self::PRODUK_DESIGN_CREATED,
            self::PRODUK_DESIGN_UPDATED,
            self::PRODUK_DESIGN_APPROVED,
            self::EXPORT_COMPLETED,
            self::IMPORT_COMPLETED,
            self::BACKUP_COMPLETED,
            self::SYNC_COMPLETED,
            self::API_CALL_SUCCESS,
            self::REPORT_GENERATED,
            self::REPORT_EXPORTED,
            self::ANALYTICS_UPDATED,
            self::TICKET_CREATED,
            self::TICKET_UPDATED,
            self::TICKET_CLOSED,
            self::FEEDBACK_SUBMITTED,
            self::ACCOUNT_CREATED,
            self::USER_CREATED,
            self::MAINTENANCE_COMPLETED,
            self::PREFERENCES_UPDATED,
            self::TWO_FACTOR_ENABLED,
        ]);
    }

    // ==================== IS FAILURE ====================
    public function isFailure(): bool
    {
        return in_array($this, [
            self::LOGIN_FAILED,
            self::SESSION_EXPIRED,
            self::DATA_DELETED,
            self::FORM_REJECTED,
            self::TRANSACTION_FAILED,
            self::TRANSACTION_CANCELLED,
            self::TRANSACTION_REFUNDED,
            self::IMPORT_FAILED,
            self::EXPORT_FAILED,
            self::SYSTEM_ERROR,
            self::SERVER_ERROR,
            self::DATABASE_ERROR,
            self::ACCOUNT_DELETED,
            self::USER_DELETED,
            self::PROJECT_DELETED,
            self::Ikm_DELETED,
            self::Ikm_REJECTED,
            self::Cots_DELETED,
            self::Cots_REMOVED,
            self::BENCHMARK_DELETED,
            self::BENCHMARK_REJECTED,
            self::DOKUMENTASI_DELETED,
            self::PRODUK_DESIGN_DELETED,
            self::PRODUK_DESIGN_REJECTED,
            self::BACKUP_FAILED,
            self::SYNC_FAILED,
            self::API_CALL_FAILED,
            self::REPORT_FAILED,
            self::UNAUTHORIZED_ACCESS,
            self::SUSPICIOUS_ACTIVITY,
            self::SECURITY_ALERT,
            self::PASSWORD_EXPIRED,
            self::MAINTENANCE_STARTED,
            self::MAINTENANCE_MODE,
            self::PROFILE_DELETED,
        ]);
    }

    // ==================== IS INFO ====================
    public function isInfo(): bool
    {
        return in_array($this, [
            self::LOGOUT,
            self::USER_LOGGED_OUT,
            self::DATA_EXPORTED,
            self::BULK_ACTION,
            self::TRANSACTION_CREATED,
            self::PROJECT_ARCHIVED,
            self::Cots_UPDATED,
            self::SYNC_STARTED,
            self::IMPORT_STARTED,
            self::EXPORT_STARTED,
            self::SYSTEM_INFO,
            self::SYSTEM_WARNING,
            self::BACKUP_STARTED,
            self::MAINTENANCE_MODE,
            self::USER_ROLE_CHANGED,
            self::USER_PERMISSION_CHANGED,
            self::TWO_FACTOR_DISABLED,
            self::REPORT_GENERATED,
        ]);
    }
}
