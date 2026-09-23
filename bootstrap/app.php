<?php

use App\Http\Middleware\EnsureUserIsGuru;
use App\Http\Middleware\LogoutAfterIdle;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'guru' => EnsureUserIsGuru::class,
        ]);

        $middleware->web(append: [
            LogoutAfterIdle::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (ThrottleRequestsException $e, Request $request) {
            $retryAfter = (int) ($e->getHeaders()['Retry-After'] ?? 60);
            $minutes = max(1, (int) ceil($retryAfter / 60));

            if ($request->is('login')) {
                return redirect()->route('login')->with('warning', 'Terlalu banyak percobaan login. Server sedang penuh / sibuk, coba lagi dalam sekitar ' . $minutes . ' menit.');
            }
            if ($request->is('register')) {
                return redirect()->route('register')->with('warning', 'Terlalu banyak percobaan registrasi. Server sedang penuh / sibuk, coba lagi dalam sekitar ' . $minutes . ' menit.');
            }
            return response()->view('errors.429', ['message' => $e->getMessage()], 429);
        });
    })->create();
