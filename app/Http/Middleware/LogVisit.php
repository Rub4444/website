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

        // 🚫 Исключаем ботов
        $botPatterns = [
            'bot', 'crawl', 'slurp', 'spider', 'bingpreview', 'yandex',
            'AhrefsBot', 'PostmanRuntime', 'curl', 'HttpClient', 'ApacheBench'
        ];
        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return $next($request);
            }
        }

        // Определяем устройство
        $device = 'desktop';
        if (preg_match('/mobile/i', $userAgent)) {
            $device = 'mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            $device = 'tablet';
        }

        // Определяем браузер
        $browser = 'Unknown';
        if (preg_match('/chrome/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/firefox/i', $userAgent)) $browser = 'Firefox';
        elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/edg/i', $userAgent)) $browser = 'Edge';
        elseif (preg_match('/opera|opr/i', $userAgent)) $browser = 'Opera';

        // Проверка повторного визита
        $recent = DB::table('visits')
            ->where('ip', $ip)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if (!$recent) {
            DB::table('visits')->insert([
                'ip' => $ip,
                'device' => $device,
                'browser' => $browser,
                'user_agent' => $userAgent,
                'path' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}
