<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Clinic;
use Illuminate\Support\Facades\Route;

// ─── Public: Chat ─────────────────────────────────────────────────────────────
Route::get('/', [ChatController::class, 'index'])->name('home');
Route::get('/chat', [ChatController::class, 'index'])->name('chat');

// Chat JSON API (web + mobile)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('diseases', [ChatController::class, 'getDiseases'])->name('diseases');
    Route::post('chat/analyze', [ChatController::class, 'analyze'])->name('chat.analyze');
    Route::post('chat/voice', [ChatController::class, 'transcribeVoice'])->name('chat.voice');
    Route::get('banners', [ChatController::class, 'getBanners'])->name('banners');
});

// ─── Auth redirect after login ────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $role = auth()->user()->role;
    return redirect($role === 'super_admin' ? route('admin.dashboard') : route('clinic.dashboard'));
})->name('dashboard');

// ─── Super Admin Panel ────────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:super_admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('clinics', Admin\ClinicController::class)->except('show');
        Route::resource('diseases', Admin\DiseaseController::class)->except('show');
        Route::resource('doctors', Admin\DoctorController::class)->except('show');
        Route::resource('banners', Admin\BannerController::class)->except('show');
    });

// ─── Clinic Panel ─────────────────────────────────────────────────────────────
Route::prefix('clinic')
    ->middleware(['auth', 'verified', 'role:clinic_admin'])
    ->name('clinic.')
    ->group(function () {
        Route::get('/', [Clinic\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('diseases', Clinic\DiseaseController::class)->except('show');
        Route::resource('doctors', Clinic\DoctorController::class)->except('show');
        Route::resource('recommendations', Clinic\RecommendationController::class)->except('show');
        Route::resource('banners', Clinic\BannerController::class)->except('show');
    });

require __DIR__.'/settings.php';
