<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyFooter
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            $response instanceof Response &&
            str_contains(strtolower($response->headers->get('Content-Type', '')), 'text/html')
        ) {
            $content = $response->getContent();

            if (!str_contains($content, '<footer')) {
                return $response;
            }

            $text = html_entity_decode(strip_tags($content), ENT_QUOTES, 'UTF-8');
            $text = preg_replace('/[\x{200E}\x{200F}\x{061C}\x{202A}-\x{202E}\x{2066}-\x{2069}]/u', '', $text);
            $text = preg_replace('/\s+/u', ' ', $text);

            $hasName = (bool) preg_match('/BASHIR\s*OSMAN/i', $text);
            $digits = preg_replace('/\D+/', '', $text);
            $hasPhone = str_contains($digits, '0544207345');

            if (!$hasName || !$hasPhone) {
                Log::warning("Footer dev info missing/altered at: {$request->url()}");
                abort(403, 'تم اكتشاف تعديل أو حذف غير مصرح به في تذييل الصفحة.');
            }
        }

        return $response;
    }
}
