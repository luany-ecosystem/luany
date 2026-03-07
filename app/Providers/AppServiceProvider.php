<?php

namespace App\Providers;

use Luany\Framework\Application;
use Luany\Framework\ServiceProvider;
use Luany\Framework\Support\Env;
use App\Support\Translator;

/**
 * AppServiceProvider
 *
 * General application bootstrapping:
 *   - Timezone
 *   - Session (cookie params + start)
 *   - URL/path constants
 *   - Application helpers
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(Application $app): void
    {
        // Translator — singleton, resolved once per request lifecycle
        $app->singleton('translator', function () use ($app) {
            $config = require $app->basePath('config/app.php');

            return new Translator(
                langPath:  $app->basePath('lang'),
                locale:    $config['locale'],
                fallback:  $config['fallback_locale'],
                supported: $config['supported_locales'],
            );
        });
    }

    public function boot(Application $app): void
    {
        $this->configureTimezone();
        $this->startSession();
        $this->defineConstants($app);
        $this->loadHelpers();
    }

    private function configureTimezone(): void
    {
        date_default_timezone_set(Env::get('APP_TIMEZONE', 'UTC'));
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => Env::get('APP_ENV') === 'production',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    private function defineConstants(Application $app): void
    {
        $baseUrl = rtrim((string) Env::get('APP_URL', 'http://localhost:8000'), '/');
        defined('BASE_URL')   || define('BASE_URL',   $baseUrl);
        defined('ASSETS_URL') || define('ASSETS_URL', BASE_URL . '/assets');
        defined('CSS_URL')    || define('CSS_URL',    ASSETS_URL . '/css');
        defined('JS_URL')     || define('JS_URL',     ASSETS_URL . '/js');
        defined('IMAGES_URL') || define('IMAGES_URL', ASSETS_URL . '/images');

        defined('LOGS_PATH')    || define('LOGS_PATH',    $app->storagePath('logs'));
        defined('STORAGE_PATH') || define('STORAGE_PATH', $app->storagePath());
        defined('VIEWS_PATH')   || define('VIEWS_PATH',   $app->viewsPath());
    }

    private function loadHelpers(): void
    {
        $helpers = app()->basePath('app/Support/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }
}