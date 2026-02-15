<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CotsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\IkmController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DetileIkmController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BackupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
    });
 Route::get('/imagesearch', [SettingsController::class, 'searchImage']);

Route::resource('/register', RegisterController::class);
//Route Privilage
Route::post('/getkabupaten',[RegisterController::class,'getkabupaten'])->name('getkabupaten');
Route::post('/getkecamatan',[RegisterController::class,'getkecamatan'])->name('getkecamatan');
Route::post('/getdesa',[RegisterController::class,'getdesa'])->name('getdesa');
//login
Route::get('/login',[LoginController::class,'index'])->name('login')->middleware('guest');
Route::post('/logout',[LoginController::class,'logout']);
Route::post('/login',[LoginController::class,'login'])->middleware('guest')->name('login.process');
Route::get('/dashboard',[DashboardController::class,'index'])->middleware('auth')->name('dashboard');
//Profile
Route::resource('/profile', ProfileController::class)->middleware('auth');
Route::post('/profile/photo/cropped', [ProfileController::class, 'updatePhotoCropped'])->name('profile.photo.cropped')->middleware('auth');
Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update')->middleware('auth');
Route::delete('/profile/photo', [ProfileController::class, 'destroy'])->name('profile.photo.remove')->middleware('auth');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update')->middleware('auth');
//backEnd
Route::get('/brainstorming',[MyController::class,'brainstorming'])->middleware('auth');

Route::get('/brainstorming',[MyController::class,'brainstorming'])->middleware('auth');
Route::get('/brainstorming/create',[MyController::class,'brainstormingInsert'])->middleware('auth');

Route::get('/ajax/search-Ikm', [MyController::class, 'searchIkm'])->name('ajax.searchIkm');

Route::get('/kurasi',[MyController::class,'kurasi'])->middleware('auth');
//Menu Project
Route::get('/project',[ProjectController::class,'index'])->middleware('auth')->name('project.index');
Route::get('/api/project/search', [ProjectController::class, 'searchProjects'])->name('api.project.search')->middleware('auth');
Route::get('/api/projects/filter', [ProjectController::class, 'filterProjects'])->name('api.projects.filter')->middleware('auth');
Route::post('/project/create',[ProjectController::class,'store'])->middleware('auth');
Route::post('/project/update',[ProjectController::class,'update'])->middleware('auth');
Route::delete('/project/hapus/{id}',[ProjectController::class,'hapus'])->middleware('auth');
//menu Ikm
Route::get('/project/dataIkm/{project:id}',[IkmController::class,'view'])->middleware('auth')->name('Ikm.index');
Route::get('/project/dataIkm/Ikm/{ikm}/edit',[IkmController::class,'edit'])->middleware('auth')->name('ikm.edit');
Route::post('/project/dataIkm/Ikm/update',[IkmController::class,'UpdateIkm'])->middleware('auth')->name('ikm.update');
Route::post('/project/dataIkm/Ikm/{id}/update',[DetileIkmController::class,'ubahFotoIkm'])->middleware('auth')->name('ikm.updatePhoto');
Route::post('/project/dataIkm/createIkm',[IkmController::class,'createIkm'])->middleware('auth');
Route::post('/project/dataIkm/tambahIkm',[IkmController::class,'tambahIkm'])->middleware('auth');

Route::post('/project/dataIkm/{id}/delete',[IkmController::class,'deleteIkm'])->middleware('auth');
Route::post('/project/dataIkm/ajax-delete',[IkmController::class,'ajaxDeleteIkm'])->middleware('auth')->name('ikm.ajaxDelete');
Route::post('/getkabupatenUpdate',[IkmController::class,'getkabupaten'])->name('getkabupatenUpdate');
Route::post('/getkecamatanUpdate',[IkmController::class,'getkecamatan'])->name('getkecamatanUpdate');
Route::post('/getdesaUpdate',[IkmController::class,'getdesa'])->name('getdesaUpdate');
Route::POST('/project/dataIkm/{id}/update',[IkmController::class,'getmemberUpdate'])->name('getmemberUpdate');
// detile Ikm
// Route::get('/project/Ikms/{id}',[DetileIkmController::class,'index'])->middleware('auth')->name('detail');
Route::get('/project/Ikms/{id_Ikm}/{id_project}',[DetileIkmController::class,'index'])->middleware('auth')->name('detail');

// Encrypted route variants for secure ID handling
Route::get('/e/Ikm/{encrypted_id}/{encrypted_project}',[DetileIkmController::class,'encryptedIndex'])->middleware('auth')->name('detail.encrypted');

// API endpoint for decrypting IDs (used by JavaScript)
Route::post('/api/decrypt-ids', [DetileIkmController::class, 'decryptIds'])->middleware('auth')->name('api.decrypt-ids');
Route::post('/project/Ikms/{id}/update',[DetileIkmController::class,'ubahFotoIkm'])->middleware('auth')->name('updatePhoto');
Route::post('/project/Ikms/{id}/bencmark',[DetileIkmController::class,'bencmark'])->middleware('auth');
Route::post('/project/Ikms/{id}/Cots',[DetileIkmController::class,'cots'])->middleware('auth');
Route::post('/project/Ikms/{id}/updateCots',[DetileIkmController::class,'Updatecots'])->middleware('auth');
Route::post('/project/Ikms/{id}/dokumentasi',[DetileIkmController::class,'dokumentasi'])->middleware('auth');
Route::post('/project/Ikms/{id}/deleteDoc',[DetileIkmController::class,'deleteDoc'])->middleware('auth');
Route::post('/project/Ikms/{id_gambar}/deletebencmark',[DetileIkmController::class,'bencmarkDelete'])->middleware('auth');
Route::post('/project/Ikms/updateBrainstorming',[DetileIkmController::class,'updateBrainstorming'])->middleware('auth');
// Auto-save endpoint for brainstorming
Route::post('/project/Ikms/auto-save-brainstorming',[DetileIkmController::class,'autoSaveBrainstorming'])->middleware('auth');

// Auto-save endpoint for COTS
Route::post('/project/Ikms/auto-save-cots',[DetileIkmController::class,'autoSaveCots'])->middleware('auth');

Route::post('/project/Ikms/{id}/tambahDesain',[DetileIkmController::class,'tambahDesain'])->middleware('auth');
Route::post('/project/Ikms/{id}/deleteDesain',[DetileIkmController::class,'deleteDesain'])->middleware('auth');
// report
Route::get('/report/brainstorming/{id}/{name}',[ReportController::class,'ReportBrainstorming'])->middleware('auth');
Route::get('/report/Cots/{id}/{name}',[ReportController::class,'ReportCots'])->middleware('auth');
Route::get('/report/Ikms/{id_project}/{nama_project}',[ReportController::class,'IkmReport'])->middleware('auth');

// public Cots
Route::get('/Cots',[DetileIkmController::class,'publik_Cots']);
Route::post('/Cots/create',[DetileIkmController::class,'Cots_save']);


// Clear application cache:
Route::get('/clean', function() {
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');

        return response()->json([
            'status' => 'success',
            'message' => 'Application cache has been cleared successfully!'
        ]);
    } catch (\Exception $e) {
        // Log error agar bisa dicek di storage/logs/laravel.log
        Log::error('Cache clear failed: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to clear cache. Please check logs.'
        ], 500);
    }
    });

// Ollama Proxy - Forward /api/generate to /api/tags on myollama.scrollwebid.com
Route::any('/ollama/{any}', [MyController::class, 'ollamaProxy'])->where('any', '.*');

// Ollama Proxy - Forward /api/generate to /api/tags
Route::any('/ollama/{any}', [MyController::class, 'ollamaProxy'])->where('any', '.*')->middleware('auth');

// Notification Routes
Route::prefix('notifications')->middleware('auth')->group(function () {
    // API endpoints for AJAX
    Route::get('/recent', [NotificationController::class, 'recent']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::get('/preferences', [NotificationController::class, 'preferences']);
    Route::put('/preferences', [NotificationController::class, 'updatePreferences']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/initialize-preferences', [NotificationController::class, 'initializePreferences']);

    // New endpoints
    Route::get('/statistics', [NotificationController::class, 'statistics']);
    Route::get('/by-category', [NotificationController::class, 'byCategory']);
    Route::get('/by-type', [NotificationController::class, 'byType']);
    Route::get('/search', [NotificationController::class, 'search']);
    Route::get('/activity-timeline', [NotificationController::class, 'activityTimeline']);
    Route::get('/recent-activities', [NotificationController::class, 'recentActivities']);
    Route::post('/mark-read-by-category', [NotificationController::class, 'markAllReadByCategory']);
    Route::post('/mark-read-by-type', [NotificationController::class, 'markAllReadByType']);
    Route::delete('/delete-read', [NotificationController::class, 'deleteRead']);
    Route::get('/export', [NotificationController::class, 'export']);

    // Page route for settings
    Route::get('/settings', function() {
        return view('pages.notifications.preferences');
    })->name('notifications.settings');

    // Individual notification operations
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/{notification}', [NotificationController::class, 'show']);
    Route::put('/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/{notification}/unread', [NotificationController::class, 'markAsUnread']);
    Route::delete('/{notification}', [NotificationController::class, 'destroy']);
    Route::delete('/', [NotificationController::class, 'destroyAll']);
});

// Dashboard API Routes
Route::prefix('api/dashboard')->middleware('auth')->group(function () {
    Route::get('/refresh-chart', [DashboardController::class, 'refreshChartData']);
    Route::get('/export', [DashboardController::class, 'export']);
});

// Settings Routes
Route::prefix('settings')->middleware('auth')->group(function () {
    // Main settings page
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');

    // Branding settings page
    Route::get('/branding', [SettingsController::class, 'branding'])->name('settings.branding');

    // Branding image update
    Route::put('/branding/image/{type}', [SettingsController::class, 'updateBrandingImage'])->name('settings.branding.image');

    // Branding text update
    Route::put('/branding/text/{key}', [SettingsController::class, 'updateBrandingText'])->name('settings.branding.text');

    // Branding toggle update
    Route::put('/branding/toggle/{key}', [SettingsController::class, 'updateBrandingToggle'])->name('settings.branding.toggle');

    // Logo settings
    Route::put('/logo/{type}', [SettingsController::class, 'updateLogo'])->name('settings.logo.update');
    Route::get('/logo/{type}/reset', [SettingsController::class, 'resetLogo'])->name('settings.logo.reset');
    Route::get('/logo/{type}/preview', [SettingsController::class, 'logoPreview'])->name('settings.logo.preview');
    Route::delete('/logo/{type}', [SettingsController::class, 'deleteLogo'])->name('settings.logo.delete');

    // Registration settings
    Route::put('/registration', [SettingsController::class, 'updateRegistration'])->name('settings.registration.update');

    // General settings
    Route::put('/general', [SettingsController::class, 'updateSettings'])->name('settings.general.update');

    // Activity logs
    Route::get('/activity-logs', [SettingsController::class, 'activityLogs'])->name('settings.activity-logs');

    // Seed defaults (admin only)
    Route::post('/seed', [SettingsController::class, 'seedDefaults'])->name('settings.seed');

});

// Backup Routes
Route::prefix('backup')->middleware(['auth', 'admin'])->group(function () {
    // Main backup page
    Route::get('/', [BackupController::class, 'index'])->name('backup.index');

    // Settings API
    Route::get('/settings', [BackupController::class, 'getSettings'])->name('backup.settings');
    Route::put('/settings', [BackupController::class, 'updateSettings'])->name('backup.settings.update');
    Route::post('/toggle-auto-backup', [BackupController::class, 'toggleAutoBackup'])->name('backup.toggle');

    // Database tables
    Route::get('/tables', [BackupController::class, 'getTables'])->name('backup.tables');
    Route::post('/estimate', [BackupController::class, 'estimateBackup'])->name('backup.estimate');

    // Backup operations
    Route::post('/full', [BackupController::class, 'createFullBackup'])->name('backup.full');
    Route::post('/per-table', [BackupController::class, 'createPerTableBackup'])->name('backup.per-table');
    Route::post('/csv', [BackupController::class, 'createCsvBackup'])->name('backup.csv');

    // Download & Delete
    Route::get('/download/{id}', [BackupController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/delete', [BackupController::class, 'deleteBackup'])->name('backup.delete');

    // History & Status
    Route::get('/history', [BackupController::class, 'getBackupHistory'])->name('backup.history');
    Route::get('/status/{id}', [BackupController::class, 'getBackupStatus'])->name('backup.status');

    // Cleanup
    Route::post('/cleanup', [BackupController::class, 'cleanupOldBackups'])->name('backup.cleanup');
});
