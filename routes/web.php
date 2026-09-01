<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurhatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BalasanController;
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

    // Curhat routes (untuk siswa)
    Route::get('/curhat', [CurhatController::class, 'createForm'])->name('curhat.create');
    Route::post('/curhat', [CurhatController::class, 'store'])->name('curhat.store');
    Route::get('/riwayat', [CurhatController::class, 'history'])->name('riwayat');
    Route::get('/riwayat/{curhat}', [CurhatController::class, 'detail'])->name('riwayat.show');
    Route::post('/riwayat/curhat/{curhat}/balas', [BalasanController::class, 'store'])->name('riwayat.reply');

    // Dashboard routes (untuk teman/konselor)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{curhat}', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::post('/dashboard/curhat/{curhat}/tangani', [DashboardController::class, 'handle'])->name('dashboard.handle');

    // Balasan routes
    Route::post('/dashboard/curhat/{curhat}/balas', [BalasanController::class, 'store'])->name('dashboard.reply');
});
