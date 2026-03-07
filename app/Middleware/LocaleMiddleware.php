<?php

namespace App\Middleware;

use Luany\Core\Http\Request;
use Luany\Core\Http\Response;
use Luany\Core\Middleware\MiddlewareInterface;

/**
 * LocaleMiddleware
 *
 * Detects the active locale on every request and sets it on the
 * Translator instance bound in the container.
 *
 * Detection order (highest to lowest priority):
 *   1. Cookie 'app_locale'          — explicit user preference
 *   2. Accept-Language HTTP header  — browser preference
 *   3. APP_LOCALE env variable      — application default
 *   4. 'en'                         — hardcoded fallback
 */
class LocaleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        /** @var \App\Support\Translator $translator */
        $translator = app('translator');

        $locale = $this->detect($request, $translator->getSupported());

        $translator->setLocale($locale);

        return $next($request);
    }

    // ── Private ───────────────────────────────────────────────

    private function detect(Request $request, array $supported): string
    {
        // 1. Cookie — explicit user preference persists across sessions
        $cookie = $_COOKIE['app_locale'] ?? null;
        if ($cookie && in_array($cookie, $supported, true)) {
            return $cookie;
        }

        // 2. Accept-Language header — browser/OS preference
        $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        if ($header) {
            $detected = $this->parseAcceptLanguage($header, $supported);
            if ($detected) {
                return $detected;
            }
        }

        // 3. APP_LOCALE env
        $env = env('APP_LOCALE', 'en');
        if (in_array($env, $supported, true)) {
            return $env;
        }

        // 4. Hardcoded fallback
        return 'en';
    }

    /**
     * Parse Accept-Language header and return the best supported locale.
     * Handles: 'pt-PT,pt;q=0.9,en;q=0.8' → 'pt'
     */
    private function parseAcceptLanguage(string $header, array $supported): ?string
    {
        $parts  = explode(',', $header);
        $parsed = [];

        foreach ($parts as $part) {
            $segments = explode(';q=', trim($part));
            $tag      = trim($segments[0]);
            $quality  = isset($segments[1]) ? (float) $segments[1] : 1.0;

            // Normalise: 'pt-PT' → 'pt'
            $lang = strtolower(explode('-', $tag)[0]);
            $parsed[$lang] = max($parsed[$lang] ?? 0, $quality);
        }

        arsort($parsed);

        foreach (array_keys($parsed) as $lang) {
            if (in_array($lang, $supported, true)) {
                return $lang;
            }
        }

        return null;
    }
}
