<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Clinic;
use Illuminate\Support\Facades\Route;

// ─── Public: Welcome (mobile-like landing) ───────────────────────────────────
Route::get('/', function () {
    return \Inertia\Inertia::render('Welcome');
})->name('home');

// Chat page (for logged-in users or kept for mobile web fallback)
Route::get('/chat', [ChatController::class, 'index'])->name('chat');

// API endpoints endi routes/api.php da (stateless, CSRF yo'q — bot/mobile uchun)

// ─── Auth redirect after login ────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'super_admin') return redirect(route('admin.dashboard'));
    if ($role === 'clinic_admin') return redirect(route('clinic.dashboard'));
    return redirect(route('chat'));
})->name('dashboard');

// ─── Super Admin Panel ────────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:super_admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('export/statistics', [Admin\DashboardController::class, 'exportStatistics'])->name('export.statistics');
        Route::resource('clinics', Admin\ClinicController::class)->except('show');
        Route::resource('diseases', Admin\DiseaseController::class)->except('show');
        Route::resource('doctors', Admin\DoctorController::class)->except('show');
        Route::resource('banners', Admin\BannerController::class)->except('show');
        Route::resource('users', Admin\UserController::class)->except('show');
        Route::resource('recommendations', Admin\RecommendationController::class)->except('show');
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
