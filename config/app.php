<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application
    |--------------------------------------------------------------------------
    */

    'name'  => env('APP_NAME', 'Luany'),
    'env'   => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url'   => env('APP_URL', 'http://localhost:8000'),

    /*
    |--------------------------------------------------------------------------
    | Localisation
    |--------------------------------------------------------------------------
    |
    | locale          — active locale, overridable by cookie / Accept-Language
    | fallback_locale — used when a key is missing from the active locale
    | supported_locales — locales outside this list are silently rejected
    |
    | To add a new language:
    |   1. Create lang/{code}.php
    |   2. Add the code to supported_locales
    |   3. Add a button in views/components/navbar.lte
    |
    */

    'locale'            => env('APP_LOCALE', 'en'),
    'fallback_locale'   => 'en',
    'supported_locales' => ['en', 'pt'],

];