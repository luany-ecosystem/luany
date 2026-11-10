<?php

namespace App\Controllers\Locale;

use Luany\Core\Http\Request;
use Luany\Core\Http\Response;
use App\Controllers\Controller;

/**
 * LocaleController
 *
 * Handles locale switching. Sets a cookie and redirects back.
 *
 * Route: GET /locale/{lang}
 *
 * The cookie persists for 1 year. The user's choice is remembered
 * across sessions without requiring a database or session layer.
 */
class LocaleController extends Controller
{
    public function switch(Request $request, string $lang): Response
    {
        /** @var \App\Support\Translator $translator */
        $translator = app('translator');

        // Silently ignore unsupported locales — never throw on bad input
        if (!$translator->isSupported($lang)) {
            $lang = $translator->getFallback();
        }

        // Persist preference in cookie — 1 year
        $expires  = time() + 60 * 60 * 24 * 365;
        $secure   = (bool) env('APP_HTTPS', false)
            || (method_exists($request, 'isSecure') && $request->isSecure());

        setcookie('app_locale', $lang, [
            'expires'  => $expires,
            'path'     => '/',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        return Response::make('', 302, ['Location' => $this->safeBackUrl($request)]);
    }

    /**
     * Redirect target for "back": the Referer path when it points to this
     * host, otherwise "/". Prevents open redirects through a forged Referer.
     */
    private function safeBackUrl(Request $request): string
    {
        $referer = (string) $request->server('HTTP_REFERER', '');
        if ($referer === '') {
            return '/';
        }

        $parts = parse_url($referer);
        if ($parts === false) {
            return '/';
        }

        $host        = strtolower((string) ($parts['host'] ?? ''));
        $requestHost = strtolower((string) preg_replace('/:\d+$/', '', (string) $request->server('HTTP_HOST', '')));

        if ($host === '' || $host !== $requestHost) {
            return '/';
        }

        $path = (string) ($parts['path'] ?? '/');
        if (!str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return '/';
        }

        return $path . (isset($parts['query']) ? '?' . $parts['query'] : '');
    }
}