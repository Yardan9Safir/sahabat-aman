<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsGuru;
use App\Http\Middleware\LogoutAfterIdle;
use App\Models\Curhat;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_AWS_ELB);
        $middleware->alias([
            'guru' => EnsureUserIsGuru::class,
            'admin' => EnsureUserIsAdmin::class,
        ]);

        $middleware->web(append: [
            AuthenticateSession::class,
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

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            $previous = $e->getPrevious();

            if (! $previous instanceof ModelNotFoundException
                || $previous->getModel() !== Curhat::class
                || ! Auth::check()) {
                return null;
            }

            return redirect()->route('dashboard')
                ->with('info', 'Percakapan ini telah ditutup oleh penerima dan sudah tidak tersedia lagi.');
        });
    })->create();
