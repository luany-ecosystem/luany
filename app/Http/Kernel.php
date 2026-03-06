<?php

namespace App\Http;

use Luany\Framework\Http\Kernel as BaseKernel;
use App\Middleware\CsrfMiddleware;

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
        CsrfMiddleware::class,
    ];

    /**
     * Routes file — relative to the routes/ directory.
     */
    protected string $routesFile = 'http.php';
}