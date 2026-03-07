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

        // Persist preference in cookie — 1 year, same-site strict
        $expires  = time() + 60 * 60 * 24 * 365;
        $secure   = (bool) env('APP_HTTPS', false);

        setcookie('app_locale', $lang, [
            'expires'  => $expires,
            'path'     => '/',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        // Redirect back — fall back to home if no Referer header
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';

        return Response::make('', 302, ['Location' => $referer]);
    }
}