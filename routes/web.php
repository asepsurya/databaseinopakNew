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
Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update')->middleware('auth');
Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove')->middleware('auth');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update')->middleware('auth');
//backEnd
Route::get('/brainstorming',[MyController::class,'brainstorming'])->middleware('auth');

Route::get('/brainstorming',[MyController::class,'brainstorming'])->middleware('auth');
Route::get('/brainstorming/create',[MyController::class,'brainstormingInsert'])->middleware('auth');

Route::get('/ajax/search-ikm', [MyController::class, 'searchIkm'])->name('ajax.searchIkm');

Route::get('/kurasi',[MyController::class,'kurasi'])->middleware('auth');
//Menu Project
Route::get('/project',[ProjectController::class,'index'])->middleware('auth')->name('project.index');
Route::get('/api/project/search', [ProjectController::class, 'searchProjects'])->name('api.project.search')->middleware('auth');
Route::post('/project/create',[ProjectController::class,'store'])->middleware('auth');
Route::post('/project/update',[ProjectController::class,'update'])->middleware('auth');
Route::post('/project/hapus/{id}',[ProjectController::class,'hapus'])->middleware('auth');
//menu Ikm
Route::get('/project/dataikm/{project:id}',[IkmController::class,'index'])->middleware('auth')->name('ikm.index');
Route::get('/project/dataikm/ikm/{ikm}/edit',[IkmController::class,'edit'])->middleware('auth')->name('ikm.edit');
Route::POST('/project/dataikm/ikm/{ikm}/update',[IkmController::class,'UpdateIkm'])->middleware('auth')->name('ikm.update');
Route::post('/project/dataikm/createIkm',[IkmController::class,'createIkm'])->middleware('auth');
Route::post('/project/dataikm/tambahIkm',[IkmController::class,'tambahIkm'])->middleware('auth');
Route::post('/project/dataikm/UpdateIkm',[IkmController::class,'UpdateIkm'])->middleware('auth');
Route::post('/project/dataikm/{id}/delete',[IkmController::class,'deleteIkm'])->middleware('auth');
Route::post('/getkabupatenUpdate',[IkmController::class,'getkabupaten'])->name('getkabupatenUpdate');
Route::post('/getkecamatanUpdate',[IkmController::class,'getkecamatan'])->name('getkecamatanUpdate');
Route::post('/getdesaUpdate',[IkmController::class,'getdesa'])->name('getdesaUpdate');
Route::post('/project/dataikm/{id}/update',[IkmController::class,'getmemberUpdate'])->name('getmemberUpdate');
// detile Ikm
// Route::get('/project/ikms/{id}',[DetileIkmController::class,'index'])->middleware('auth')->name('detail');
Route::get('/project/ikms/{id_ikm}/{id_project}',[DetileIkmController::class,'index'])->middleware('auth')->name('detail');

// Encrypted route variants for secure ID handling
Route::get('/e/ikm/{encrypted_id}/{encrypted_project}',[DetileIkmController::class,'encryptedIndex'])->middleware('auth')->name('detail.encrypted');

// API endpoint for decrypting IDs (used by JavaScript)
Route::post('/api/decrypt-ids', [DetileIkmController::class, 'decryptIds'])->middleware('auth')->name('api.decrypt-ids');
Route::post('/project/ikms/{id}/update',[DetileIkmController::class,'ubahFotoIkm'])->name('updatePhoto');
Route::post('/project/ikms/{id}/bencmark',[DetileIkmController::class,'bencmark'])->middleware('auth');
Route::post('/project/ikms/{id}/cots',[DetileIkmController::class,'cots'])->middleware('auth');
Route::post('/project/ikms/{id}/a',[DetileIkmController::class,'Updatecots'])->middleware('auth');
Route::post('/project/ikms/{id}/dokumentasi',[DetileIkmController::class,'dokumentasi'])->middleware('auth');
Route::post('/project/ikms/{id}/deleteDoc',[DetileIkmController::class,'deleteDoc'])->middleware('auth');
Route::post('/project/ikms/{id_gambar}/deletebencmark',[DetileIkmController::class,'bencmarkDelete'])->middleware('auth');
Route::post('/project/ikms/updateBrainstorming',[DetileIkmController::class,'updateBrainstorming'])->middleware('auth');
// Auto-save endpoint for brainstorming
Route::post('/project/ikms/auto-save-brainstorming',[DetileIkmController::class,'autoSaveBrainstorming'])->middleware('auth');

Route::post('/project/ikms/{id}/tambahDesain',[DetileIkmController::class,'tambahDesain'])->middleware('auth');
Route::post('/project/ikms/{id}/deleteDesain',[DetileIkmController::class,'deleteDesain'])->middleware('auth');
// report
Route::get('/report/brainstorming/{id}/{name}',[ReportController::class,'ReportBrainstorming'])->middleware('auth');
Route::get('/report/cots/{id}/{name}',[ReportController::class,'Reportcots'])->middleware('auth');
Route::get('/report/ikms/{id_project}/{nama_project}',[ReportController::class,'ikmReport'])->middleware('auth');

// public Cots
Route::get('/cots',[DetileIkmController::class,'publik_cots']);
Route::post('/cots/create',[DetileIkmController::class,'cots_save']);


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
