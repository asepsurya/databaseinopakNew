<?php

namespace App\Enums;

enum NotificationType: string
{
    // Authentication
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case LOGIN_FAILED = 'login_failed';
    case PASSWORD_RESET = 'password_reset';
    case PASSWORD_CHANGED = 'password_changed';

    // Data Operations
    case DATA_CREATED = 'data_created';
    case DATA_UPDATED = 'data_updated';
    case DATA_DELETED = 'data_deleted';
    case DATA_RESTORED = 'data_restored';

    // Form Submissions
    case FORM_SUBMITTED = 'form_submitted';
    case FORM_APPROVED = 'form_approved';
    case FORM_REJECTED = 'form_rejected';

    // Profile
    case PROFILE_UPDATED = 'profile_updated';
    case PROFILE_PHOTO_UPDATED = 'profile_photo_updated';
    case ACCOUNT_DELETED = 'account_deleted';

    // Transactional
    case TRANSACTION_CREATED = 'transaction_created';
    case TRANSACTION_COMPLETED = 'transaction_completed';
    case TRANSACTION_FAILED = 'transaction_failed';
    case TRANSACTION_CANCELLED = 'transaction_cancelled';

    // System
    case SYSTEM_ERROR = 'system_error';
    case SYSTEM_WARNING = 'system_warning';
    case SYSTEM_INFO = 'system_info';
    case BACKUP_COMPLETED = 'backup_completed';
    case MAINTENANCE_MODE = 'maintenance_mode';

    // Project/IKM specific
    case PROJECT_CREATED = 'project_created';
    case PROJECT_UPDATED = 'project_updated';
    case PROJECT_DELETED = 'project_deleted';
    case IKM_CREATED = 'ikm_created';
    case IKM_UPDATED = 'ikm_updated';
    case IKM_DELETED = 'ikm_deleted';
    case COTS_CREATED = 'cots_created';
    case COTS_UPDATED = 'cots_updated';
    case COTS_DELETED = 'cots_deleted';

    // Export/Import
    case EXPORT_COMPLETED = 'export_completed';
    case IMPORT_COMPLETED = 'import_completed';
    case IMPORT_FAILED = 'import_failed';

    public function getIcon(): string
    {
        return match($this) {
            self::LOGIN, self::LOGOUT, self::LOGIN_FAILED => 'ti ti-login',
            self::PASSWORD_RESET, self::PASSWORD_CHANGED => 'ti ti-key',
            self::DATA_CREATED => 'ti ti-plus',
            self::DATA_UPDATED => 'ti ti-pencil',
            self::DATA_DELETED, self::ACCOUNT_DELETED => 'ti ti-trash',
            self::DATA_RESTORED => 'ti ti-recycle',
            self::FORM_SUBMITTED => 'ti ti-file-description',
            self::FORM_APPROVED => 'ti ti-check',
            self::FORM_REJECTED => 'ti ti-x',
            self::PROFILE_UPDATED, self::PROFILE_PHOTO_UPDATED => 'ti ti-user',
            self::TRANSACTION_CREATED => 'ti tiReceipt',
            self::TRANSACTION_COMPLETED => 'ti ti-check',
            self::TRANSACTION_FAILED, self::IMPORT_FAILED, self::SYSTEM_ERROR => 'ti ti-alert-circle',
            self::TRANSACTION_CANCELLED => 'ti ti-ban',
            self::SYSTEM_WARNING => 'ti ti-exclamation-triangle',
            self::SYSTEM_INFO => 'ti ti-info-circle',
            self::BACKUP_COMPLETED => 'ti ti-database',
            self::MAINTENANCE_MODE => 'ti ti-tools',
            self::PROJECT_CREATED, self::IKM_CREATED, self::COTS_CREATED => 'ti ti-folder-plus',
            self::PROJECT_UPDATED, self::IKM_UPDATED, self::COTS_UPDATED => 'ti ti-folder-edit',
            self::PROJECT_DELETED, self::IKM_DELETED, self::COTS_DELETED => 'ti ti-folder-minus',
            self::EXPORT_COMPLETED, self::IMPORT_COMPLETED => 'ti ti-download',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::LOGIN, self::LOGOUT, self::DATA_CREATED, self::FORM_APPROVED,
            self::TRANSACTION_COMPLETED, self::EXPORT_COMPLETED, self::IMPORT_COMPLETED,
            self::BACKUP_COMPLETED, self::PROJECT_CREATED, self::IKM_CREATED, self::COTS_CREATED,
            self::PROFILE_PHOTO_UPDATED => 'success',

            self::LOGIN_FAILED, self::DATA_DELETED, self::FORM_REJECTED,
            self::TRANSACTION_FAILED, self::IMPORT_FAILED, self::SYSTEM_ERROR,
            self::ACCOUNT_DELETED, self::PROJECT_DELETED, self::IKM_DELETED, self::COTS_DELETED => 'danger',

            self::PASSWORD_RESET, self::PASSWORD_CHANGED, self::DATA_UPDATED,
            self::FORM_SUBMITTED, self::PROJECT_UPDATED, self::IKM_UPDATED, self::COTS_UPDATED,
            self::PROFILE_UPDATED => 'info',

            self::TRANSACTION_CANCELLED, self::SYSTEM_WARNING => 'warning',

            self::DATA_RESTORED, self::MAINTENANCE_MODE => 'primary',

            self::SYSTEM_INFO, self::TRANSACTION_CREATED => 'secondary',
        };
    }

    public function getTitle(): string
    {
        return match($this) {
            self::LOGIN => 'Login Berhasil',
            self::LOGOUT => 'Logout',
            self::LOGIN_FAILED => 'Login Gagal',
            self::PASSWORD_RESET => 'Reset Password',
            self::PASSWORD_CHANGED => 'Password Diubah',
            self::DATA_CREATED => 'Data Baru',
            self::DATA_UPDATED => 'Data Diperbarui',
            self::DATA_DELETED => 'Data Dihapus',
            self::DATA_RESTORED => 'Data Dipulihkan',
            self::FORM_SUBMITTED => 'Form Dikirim',
            self::FORM_APPROVED => 'Form Disetujui',
            self::FORM_REJECTED => 'Form Ditolak',
            self::PROFILE_UPDATED => 'Profil Diperbarui',
            self::PROFILE_PHOTO_UPDATED => 'Foto Profil Diubah',
            self::ACCOUNT_DELETED => 'Akun Dihapus',
            self::TRANSACTION_CREATED => 'Transaksi Baru',
            self::TRANSACTION_COMPLETED => 'Transaksi Selesai',
            self::TRANSACTION_FAILED => 'Transaksi Gagal',
            self::TRANSACTION_CANCELLED => 'Transaksi Dibatalkan',
            self::SYSTEM_ERROR => 'Kesalahan Sistem',
            self::SYSTEM_WARNING => 'Peringatan Sistem',
            self::SYSTEM_INFO => 'Informasi Sistem',
            self::BACKUP_COMPLETED => 'Backup Selesai',
            self::MAINTENANCE_MODE => 'Mode Pemeliharaan',
            self::PROJECT_CREATED => 'Proyek Baru',
            self::PROJECT_UPDATED => 'Proyek Diperbarui',
            self::PROJECT_DELETED => 'Proyek Dihapus',
            self::IKM_CREATED => 'Data IKM Baru',
            self::IKM_UPDATED => 'Data IKM Diperbarui',
            self::IKM_DELETED => 'Data IKM Dihapus',
            self::COTS_CREATED => 'Data COTS Baru',
            self::COTS_UPDATED => 'Data COTS Diperbarui',
            self::COTS_DELETED => 'Data COTS Dihapus',
            self::EXPORT_COMPLETED => 'Export Selesai',
            self::IMPORT_COMPLETED => 'Import Selesai',
            self::IMPORT_FAILED => 'Import Gagal',
        };
    }

    public function getCategory(): string
    {
        return match($this) {
            self::LOGIN, self::LOGOUT, self::LOGIN_FAILED,
            self::PASSWORD_RESET, self::PASSWORD_CHANGED => 'authentication',

            self::DATA_CREATED, self::DATA_UPDATED, self::DATA_DELETED,
            self::DATA_RESTORED => 'data_operation',

            self::FORM_SUBMITTED, self::FORM_APPROVED, self::FORM_REJECTED => 'form',

            self::PROFILE_UPDATED, self::PROFILE_PHOTO_UPDATED, self::ACCOUNT_DELETED => 'profile',

            self::TRANSACTION_CREATED, self::TRANSACTION_COMPLETED,
            self::TRANSACTION_FAILED, self::TRANSACTION_CANCELLED => 'transaction',

            self::SYSTEM_ERROR, self::SYSTEM_WARNING, self::SYSTEM_INFO,
            self::BACKUP_COMPLETED, self::MAINTENANCE_MODE => 'system',

            self::PROJECT_CREATED, self::PROJECT_UPDATED, self::PROJECT_DELETED,
            self::IKM_CREATED, self::IKM_UPDATED, self::IKM_DELETED,
            self::COTS_CREATED, self::COTS_UPDATED, self::COTS_DELETED => 'content',

            self::EXPORT_COMPLETED, self::IMPORT_COMPLETED, self::IMPORT_FAILED => 'import_export',
        };
    }

    public function isSuccess(): bool
    {
        return in_array($this, [
            self::LOGIN,
            self::DATA_CREATED,
            self::DATA_UPDATED,
            self::DATA_DELETED,
            self::DATA_RESTORED,
            self::FORM_APPROVED,
            self::FORM_SUBMITTED,
            self::PROFILE_UPDATED,
            self::PROFILE_PHOTO_UPDATED,
            self::TRANSACTION_CREATED,
            self::TRANSACTION_COMPLETED,
            self::PROJECT_CREATED,
            self::PROJECT_UPDATED,
            self::PROJECT_DELETED,
            self::IKM_CREATED,
            self::IKM_UPDATED,
            self::IKM_DELETED,
            self::COTS_CREATED,
            self::COTS_UPDATED,
            self::COTS_DELETED,
            self::EXPORT_COMPLETED,
            self::IMPORT_COMPLETED,
            self::BACKUP_COMPLETED,
        ]);
    }

    public function isFailure(): bool
    {
        return in_array($this, [
            self::LOGIN_FAILED,
            self::TRANSACTION_FAILED,
            self::TRANSACTION_CANCELLED,
            self::IMPORT_FAILED,
            self::SYSTEM_ERROR,
            self::ACCOUNT_DELETED,
            self::FORM_REJECTED,
        ]);
    }
}
