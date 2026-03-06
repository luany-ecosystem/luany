<?php

/**
 * Luany Application Bootstrap
 *
 * Single responsibility: create and configure the Application instance.
 * Returns $app so public/index.php can run the Kernel.
 *
 * Sequence:
 *   1. Autoloader
 *   2. Application instance
 *   3. Environment variables
 *   4. Service Providers
 *   5. Return $app — Kernel runs in index.php
 */

$ROOT = dirname(__DIR__);

// 1. Autoloader
require $ROOT . '/vendor/autoload.php';

use Luany\Framework\Application;
use Luany\Framework\Support\Env;

// 2. Application
$app = new Application($ROOT);

// 3. Environment — load .env before anything reads it
Env::load($ROOT);
Env::required(['APP_ENV', 'APP_URL']);

// 4. Service Providers — register before kernel boots
$app->register(new App\Providers\AppServiceProvider());
$app->register(new App\Providers\DatabaseServiceProvider());

// 5. Bind the application's custom Kernel
$app->singleton(
    \Luany\Framework\Http\Kernel::class,
    fn($app) => new App\Http\Kernel($app)
);

// 6. Bind the application's custom Error Handler
$app->singleton(
    \Luany\Framework\Exceptions\Handler::class,
    fn() => new App\Exceptions\Handler((bool) Env::get('APP_DEBUG', false))
);

return $app;