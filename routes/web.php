<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurhatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CurhatController::class, 'landing'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/curhat', [CurhatController::class, 'createForm'])->name('curhat.create');
    Route::post('/curhat', [CurhatController::class, 'store'])->name('curhat.store');
    Route::get('/riwayat', [CurhatController::class, 'history'])->name('riwayat');
    Route::get('/riwayat/{curhat}', [CurhatController::class, 'detail'])->name('riwayat.show');

    Route::get('/dashboard', [CurhatController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/{curhat}', [CurhatController::class, 'recipientDetail'])->name('dashboard.show');
    Route::post('/dashboard/curhat/{curhat}/balas', [CurhatController::class, 'reply'])->name('dashboard.reply');
    Route::post('/dashboard/curhat/{curhat}/tangani', [CurhatController::class, 'handle'])->name('dashboard.handle');
});
