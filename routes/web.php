<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurhatController;
use App\Http\Controllers\QuoteController;
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

    Route::get('/dashboard', [CurhatController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/{curhat}', [CurhatController::class, 'recipientDetail'])->name('dashboard.show');
    Route::post('/dashboard/curhat/{curhat}/balas', [CurhatController::class, 'reply'])->name('dashboard.reply');
    Route::post('/dashboard/curhat/{curhat}/tangani', [CurhatController::class, 'handle'])->name('dashboard.handle');

    Route::prefix('admin')->name('admin.')->middleware('guru')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/curhat/{curhat}', [AdminController::class, 'show'])->name('show');
        Route::get('/akun', [AdminController::class, 'account'])->name('account');
        Route::put('/akun', [AdminController::class, 'accountUpdate'])->name('account.update');

        Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
        Route::get('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
        Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
        Route::get('/quotes/{quote}/edit', [QuoteController::class, 'edit'])->name('quotes.edit');
        Route::put('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
        Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
        Route::post('/quotes/{quote}/harian', [QuoteController::class, 'setDaily'])->name('quotes.daily');
    });
});
