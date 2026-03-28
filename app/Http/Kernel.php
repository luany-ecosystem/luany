<?php

namespace App\Http;

use Luany\Framework\Http\Kernel as BaseKernel;
use App\Http\Middleware\DevMiddleware;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\CsrfMiddleware;

/**
 * Application HTTP Kernel
 *
 * Global middleware runs on every request, before routing.
 * Route-level middleware is registered in routes/http.php.
 *
 * Middleware order is outermost-first:
 *   DevMiddleware    — FIRST: wraps the full pipeline, sees the final
 *                      Response. In production (APP_ENV != development)
 *                      it's a zero-cost passthrough — single env check.
 *   LocaleMiddleware — locale detection before session/routing
 *   CsrfMiddleware   — CSRF protection on state-changing requests
 */
class Kernel extends BaseKernel
{
    /**
     * Global middleware — applied to every request.
     *
     * @var array<int, class-string>
     */
    protected array $middleware = [
        DevMiddleware::class,     // dev-only: LDE live reload injection
        LocaleMiddleware::class,  // locale detection — before everything else
        CsrfMiddleware::class,
    ];

    /**
     * All route files are automatically loaded by the framework.
     */
}