<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Сохраняем только первые визиты одного IP в течение 1 часа
        $ip = $request->ip();
        $path = $request->path();
        $userAgent = $request->userAgent();

        $recent = DB::table('visits')
            ->where('ip', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if (!$recent) {
            DB::table('visits')->insert([
                'ip' => $ip,
                'user_agent' => $userAgent,
                'path' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}

