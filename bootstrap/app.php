<?php

use App\Http\Middleware\EnsureUserIsGuru;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, Request $request) {
            if ($request->is('login')) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.',
                ]);
            }
            if ($request->is('register')) {
                return redirect()->route('register')->withErrors([
                    'name' => 'Terlalu banyak percobaan registrasi. Silakan coba lagi dalam 1 menit.',
                ]);
            }
            return response()->view('errors.429', ['message' => $e->getMessage()], 429);
        });
    })->create();
