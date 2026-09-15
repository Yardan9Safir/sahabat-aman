<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurhatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CurhatController::class, 'landing'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profil', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('/curhat', [CurhatController::class, 'createForm'])->name('curhat.create');
    Route::post('/curhat', [CurhatController::class, 'store'])->name('curhat.store');
    Route::get('/riwayat', [CurhatController::class, 'history'])->name('riwayat');
    Route::get('/riwayat/{curhat}', [CurhatController::class, 'detail'])->name('riwayat.show');

    Route::get('/dashboard', [CurhatController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/{curhat}', [CurhatController::class, 'recipientDetail'])->name('dashboard.show');
    Route::post('/dashboard/curhat/{curhat}/balas', [CurhatController::class, 'reply'])->name('dashboard.reply');
    Route::post('/dashboard/curhat/{curhat}/tangani', [CurhatController::class, 'handle'])->name('dashboard.handle');

    Route::prefix('admin')->name('admin.')->middleware('guru')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/curhat/{curhat}', [AdminController::class, 'show'])->name('show');
        Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
        Route::get('/siswa/{siswa}/edit', [AdminController::class, 'siswaEdit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [AdminController::class, 'siswaUpdate'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [AdminController::class, 'siswaDestroy'])->name('siswa.destroy');
        Route::get('/akun', [AdminController::class, 'account'])->name('account');
        Route::put('/akun', [AdminController::class, 'accountUpdate'])->name('account.update');
    });
});
