<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $csp = "default-src 'self'; "
             . "script-src 'self' https://www.googletagmanager.com https://www.google-analytics.com; "
             . "connect-src 'self' https://www.google-analytics.com https://stats.g.doubleclick.net; "
             . "img-src 'self' https://www.google-analytics.com https://stats.g.doubleclick.net data:; "
             . "style-src 'self' 'unsafe-inline';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
