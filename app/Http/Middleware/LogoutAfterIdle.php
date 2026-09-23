<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogoutAfterIdle
{
    public const IDLE_MINUTES = 2;

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity_at');

            if ($lastActivity instanceof \Carbon\CarbonInterface) {
                $lastActivity = $lastActivity->timestamp;
            }
            $lastActivity = (int) $lastActivity;

            \Illuminate\Support\Facades\Log::error('idle-debug2', [
                'auth' => Auth::check(),
                'last' => $lastActivity,
                'now' => now()->timestamp,
                'diff' => $lastActivity > 0 ? now()->timestamp - $lastActivity : null,
            ]);

            if ($lastActivity > 0 && now()->timestamp - $lastActivity >= self::IDLE_MINUTES * 60) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('warning', 'Sesi kamu berakhir karena tidak ada aktivitas selama ' . self::IDLE_MINUTES . ' menit. Silakan masuk kembali.');
            }

            $request->session()->put('last_activity_at', now()->timestamp);
        }

        $response = $next($request);

        \Illuminate\Support\Facades\Log::error('idle-after', [
            'status' => $response->getStatusCode(),
            'location' => $response->headers->get('Location'),
        ]);

        return $response;
    }
}