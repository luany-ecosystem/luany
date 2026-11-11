<?php

namespace App\Providers;

use Luany\Framework\Application;
use Luany\Framework\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bind your services into the container.
     */
    public function register(Application $app): void
    {
        //
    }

    /**
     * Run code once every provider is registered.
     */
    public function boot(Application $app): void
    {
        //
    }
}
