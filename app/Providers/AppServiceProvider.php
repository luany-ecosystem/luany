<?php

namespace App\Providers;

use Luany\Framework\Application;
use Luany\Framework\ServiceProvider;
use Luany\Framework\Support\Env;
use Luany\Framework\Support\Translator;

/**
 * AppServiceProvider
 *
 * General application bootstrapping:
 *   - Translator
 *   - Timezone
 *   - Session (cookie params + start)
 *   - URL/path constants
 *   - Application helpers
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(Application $app): void
    {
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
        $this->defineConstants($app);
        $this->loadHelpers();
    }

    private function configureTimezone(): void
    {
        date_default_timezone_set(Env::get('APP_TIMEZONE', 'UTC'));
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