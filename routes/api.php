<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (stateless, no CSRF, no session)
|--------------------------------------------------------------------------
| Bu yerda web, mobile va bot uchun umumiy API endpoint'lar.
| Login shart emas — barcha endpoint'lar public.
| Prefix: /api (avtomatik qo'shiladi)
|
| Bot uchun endpoint'lar:
|   GET  /api/diseases?age_category=bola&search=gripp&per_page=100
|   POST /api/chat/analyze  {disease_id, gender, age_category, symptoms}
|   POST /api/chat/voice    multipart: audio
|   GET  /api/banners?position=sidebar
|   GET  /api/health
|--------------------------------------------------------------------------
*/

// ─── Kasalliklar ro'yxati ────────────────────────────────────────────────────
Route::get('diseases', [ChatController::class, 'getDiseases'])
    ->name('diseases');

// ─── AI tahlil (simptom → tashxis + shifokor) ───────────────────────────────
Route::post('chat/analyze', [ChatController::class, 'analyze'])
    ->middleware('api.limit:10,1')
    ->name('chat.analyze');

// ─── Ovozni matnga aylantirish (Whisper) ─────────────────────────────────────
Route::post('chat/voice', [ChatController::class, 'transcribeVoice'])
    ->middleware('api.limit:5,1')
    ->name('chat.voice');

// ─── Bannerlar ───────────────────────────────────────────────────────────────
Route::get('banners', [ChatController::class, 'getBanners'])
    ->name('banners');

// ─── Health check ────────────────────────────────────────────────────────────
Route::get('health', fn () => response()->json([
    'status' => 'ok',
    'time'   => now()->toIso8601String(),
]))->name('health');
