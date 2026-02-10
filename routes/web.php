<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;
use App\Http\Controllers\IkmController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CotsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DetileIkmController;

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

Route::resource('/register', RegisterController::class);
//Route Privilage
Route::post('/getkabupaten',[RegisterController::class,'getkabupaten'])->name('getkabupaten');
Route::post('/getkecamatan',[RegisterController::class,'getkecamatan'])->name('getkecamatan');
Route::post('/getdesa',[RegisterController::class,'getdesa'])->name('getdesa');
//login
Route::get('/login',[LoginController::class,'index'])->name('login')->middleware('guest');
Route::post('/logout',[LoginController::class,'logout']);
Route::post('/login',[LoginController::class,'login'])->middleware('guest')->name('login.process');
Route::get('/dashboard',[LoginController::class,'dashboard'])->middleware('auth');
//Profile
Route::resource('/profile', ProfileController::class)->middleware('auth');
//backEnd
Route::get('/brainstorming',[MyController::class,'brainstorming'])->middleware('auth');
Route::get('/brainstorming',[MyController::class,'brainstorming'])->middleware('auth');
Route::get('/brainstorming/create',[MyController::class,'brainstormingInsert'])->middleware('auth');

Route::get('/ajax/search-ikm', [MyController::class, 'searchIkm'])->name('ajax.searchIkm');

Route::get('/kurasi',[MyController::class,'kurasi'])->middleware('auth');
//Menu Project
Route::get('/project',[ProjectController::class,'index'])->middleware('auth');
Route::post('/project/create',[ProjectController::class,'store'])->middleware('auth');
Route::post('/project/update',[ProjectController::class,'update'])->middleware('auth');
Route::post('/project/hapus/{id}',[ProjectController::class,'hapus'])->middleware('auth');
//menu Ikm
Route::get('/project/dataikm/{project:id}',[IkmController::class,'index'])->middleware('auth');
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
Route::post('/project/ikms/{id}/update',[DetileIkmController::class,'ubahFotoIkm'])->name('updatePhoto');
Route::post('/project/ikms/{id}/bencmark',[DetileIkmController::class,'bencmark'])->middleware('auth');
Route::post('/project/ikms/{id}/cots',[DetileIkmController::class,'cots'])->middleware('auth');
Route::post('/project/ikms/{id}/a',[DetileIkmController::class,'Updatecots'])->middleware('auth');
Route::post('/project/ikms/{id}/dokumentasi',[DetileIkmController::class,'dokumentasi'])->middleware('auth');
Route::post('/project/ikms/{id}/deleteDoc',[DetileIkmController::class,'deleteDoc'])->middleware('auth');
Route::post('/project/ikms/{id_gambar}/deletebencmark',[DetileIkmController::class,'bencmarkDelete'])->middleware('auth');
Route::post('/project/ikms/updateBrainstorming',[DetileIkmController::class,'updateBrainstorming'])->middleware('auth');

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

