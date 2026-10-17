<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Guru\CurhatController as GuruCurhatController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\LoginDisplayController as GuruLoginDisplayController;
use App\Http\Controllers\Guru\QuoteController as GuruQuoteController;
use App\Http\Controllers\Guru\RekapController;
use App\Http\Controllers\KonselorSebaya\DashboardController as KonselorDashboardController;
use App\Http\Controllers\KonselorSebaya\SesiPendampinganController;
use App\Http\Controllers\Siswa\CurhatController as SiswaCurhatController;
use App\Http\Controllers\Siswa\QuoteController as SiswaQuoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publik
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'landing'])->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
});

/*
|--------------------------------------------------------------------------
| Semua role login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profil', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profil/keluar-perangkat-lain', [AuthController::class, 'logoutOtherDevices'])->name('profile.logout-other-devices');

    // Penerus tunggal: diarahkan ke panel sesuai role.
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Quotes — global, bisa diisi siswa, konselor sebaya, guru, dan admin.
    Route::get('/quotes', [SiswaQuoteController::class, 'index'])->name('quotes.index');
    Route::get('/quotes/data', [SiswaQuoteController::class, 'dataTable'])->name('quotes.data');
    Route::get('/quotes/create', [SiswaQuoteController::class, 'create'])->name('quotes.create');
    Route::post('/quotes', [SiswaQuoteController::class, 'store'])->name('quotes.store');
    Route::get('/quotes/{quote}/edit', [SiswaQuoteController::class, 'edit'])->name('quotes.edit');
    Route::put('/quotes/{quote}', [SiswaQuoteController::class, 'update'])->name('quotes.update');
    Route::delete('/quotes/{quote}', [SiswaQuoteController::class, 'destroy'])->name('quotes.destroy');
    Route::post('/quotes/{quote}/harian', [SiswaQuoteController::class, 'setDaily'])->name('quotes.daily');

    /*
    | Siswa
    */
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/', [SiswaCurhatController::class, 'index'])->name('dashboard');

        Route::get('/curhat/create', [SiswaCurhatController::class, 'create'])->name('curhat.create');
        Route::post('/curhat', [SiswaCurhatController::class, 'store'])->name('curhat.store');
        Route::get('/curhat/{curhat}', [SiswaCurhatController::class, 'show'])->name('curhat.show');
        Route::post('/curhat/{curhat}/balas', [SiswaCurhatController::class, 'reply'])->name('curhat.reply');
    });

    /*
    | Konselor Sebaya
    */
    Route::prefix('konselor')->name('konselor.')->group(function () {
        Route::get('/', [KonselorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/sesi/{curhat}', [SesiPendampinganController::class, 'show'])->name('sesi.show');
        Route::post('/sesi/{curhat}/balas', [SesiPendampinganController::class, 'reply'])->name('sesi.reply');
        Route::post('/sesi/{curhat}/tangani', [SesiPendampinganController::class, 'handle'])->name('sesi.handle');
        Route::post('/sesi/{curhat}/minta-bantuan', [SesiPendampinganController::class, 'mintaBantuan'])->name('sesi.minta-bantuan');
        Route::delete('/sesi/{curhat}', [SesiPendampinganController::class, 'destroy'])->name('sesi.destroy');
    });

    /*
    | Guru BK — satu-satunya role yang boleh kelola quotes, QOTD,
    | dan tampilan halaman login.
    */
    Route::prefix('guru')->name('guru.')->middleware('guru')->group(function () {
        Route::get('/', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');

        Route::get('/curhat', [GuruCurhatController::class, 'index'])->name('curhat.index');
        Route::get('/curhat/{curhat}', [GuruCurhatController::class, 'show'])->name('curhat.show');
        Route::post('/curhat/{curhat}/balas', [GuruCurhatController::class, 'reply'])->name('curhat.reply');
        Route::post('/curhat/{curhat}/tangani', [GuruCurhatController::class, 'handle'])->name('curhat.handle');
        Route::delete('/curhat/{curhat}', [GuruCurhatController::class, 'destroy'])->name('curhat.destroy');

        // Quotes — CRUD + QOTD khusus panel guru.
        Route::get('/quotes', [GuruQuoteController::class, 'index'])->name('quotes.index');
        Route::get('/quotes/data', [GuruQuoteController::class, 'dataTable'])->name('quotes.data');
        Route::get('/quotes/create', [GuruQuoteController::class, 'create'])->name('quotes.create');
        Route::post('/quotes', [GuruQuoteController::class, 'store'])->name('quotes.store');
        Route::get('/quotes/{quote}/edit', [GuruQuoteController::class, 'edit'])->name('quotes.edit');
        Route::put('/quotes/{quote}', [GuruQuoteController::class, 'update'])->name('quotes.update');
        Route::delete('/quotes/{quote}', [GuruQuoteController::class, 'destroy'])->name('quotes.destroy');
        Route::post('/quotes/{quote}/harian', [GuruQuoteController::class, 'setDaily'])->name('quotes.daily');

        // Tampilan halaman login — hanya guru.
        Route::get('/tampilan-login', [GuruLoginDisplayController::class, 'index'])->name('login-display.index');
        Route::put('/tampilan-login', [GuruLoginDisplayController::class, 'update'])->name('login-display.update');
    });

    /*
    | Admin — dashboard, pengguna, dan akun saja.
    */
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/akun', [AdminDashboardController::class, 'account'])->name('account');
        Route::put('/akun', [AdminDashboardController::class, 'accountUpdate'])->name('account.update');

        Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
        Route::get('/pengguna/data', [UserController::class, 'dataTable'])->name('users.data');
        Route::get('/pengguna/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/pengguna', [UserController::class, 'store'])->name('users.store');
        Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Pengaturan kelas yang diampu tiap guru BK.
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::put('/kelas/{user}', [KelasController::class, 'update'])->name('kelas.update');
    });
});
