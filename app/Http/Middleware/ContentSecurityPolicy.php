<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Проверяем, что ответ поддерживает заголовки
        if (method_exists($response, 'headers')) {
            $csp = "default-src 'self'; "
                 . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com; "
                 . "connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com; "
                 . "img-src 'self' https://www.google-analytics.com https://www.googletagmanager.com data:; "
                 . "style-src 'self' 'unsafe-inline'; "
                 . "font-src 'self';";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
