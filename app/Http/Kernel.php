<?php

namespace App\Http;

use Luany\Framework\Http\Kernel as BaseKernel;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\CsrfMiddleware;

/**
 * Application HTTP Kernel
 *
 * Global middleware runs on every request, before routing.
 * Route-level middleware is registered in routes/http.php.
 */
class Kernel extends BaseKernel
{
    /**
     * Global middleware — applied to every request.
     */
    protected array $middleware = [
        LocaleMiddleware::class,  // locale detection — before everything
        CsrfMiddleware::class,
    ];

    /**
    * All route files are automatically loaded by the framework
    */
}