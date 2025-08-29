<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogVisit
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $path = $request->path();
        $userAgent = $request->userAgent();

        // 🚫 Исключаем локальные IP
        if ($ip === '127.0.0.1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return $next($request);
        }

        // 🚫 Исключаем ботов и инструменты
        $blockedAgents = ['PostmanRuntime', 'curl', 'HttpClient', 'ApacheBench'];
        foreach ($blockedAgents as $bad) {
            if (stripos($userAgent, $bad) !== false) {
                return $next($request);
            }
        }

        // Проверка: был ли визит с этого IP в последние 10 минут
        $recent = DB::table('visits')
            ->where('ip', $ip)
            ->where('created_at', '>=', now()->subMinutes(10))
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
