<?php

return [
    'name'     => env('APP_NAME', 'Luany'),
    'env'      => env('APP_ENV', 'production'),
    'debug'    => (bool) env('APP_DEBUG', false),
    'url'      => env('APP_URL', 'http://localhost:8000'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    // Translations live in lang/{locale}.php — use __('key') in code and views.
    'locale'            => env('APP_LOCALE', 'en'),
    'fallback_locale'   => 'en',
    'supported_locales' => ['en'],

    // Template syntax: 'pt' adds Portuguese directive names (@se, @paracada,
    // @secao…) to the English ones in .lte views.
    'syntax' => env('LUANY_SYNTAX', 'en'),

    // Language of the messages for developers (CLI, diagnostics): 'en' or 'pt'.
    'dev_language' => env('LUANY_DEV_LANGUAGE', 'en'),
];
