<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimitMiddleware
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 15, int $decayMinutes = 1): Response
    {
        $key = 'api:' . ($request->ip() ?? 'unknown');

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => "So'rovlar limiti oshdi. {$seconds} soniyadan keyin qayta urinib ko'ring.",
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }
}
