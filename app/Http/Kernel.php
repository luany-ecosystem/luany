<?php

namespace App\Http;

use App\Http\Middleware\CsrfMiddleware;
use Luany\Framework\Http\Kernel as BaseKernel;
use Luany\Framework\Http\Middleware\LiveReloadMiddleware;

class Kernel extends BaseKernel
{
    /**
     * Global middleware, run on every request in this order.
     *
     * @var array<int, class-string>
     */
    protected array $middleware = [
        LiveReloadMiddleware::class, // `luany dev` live reload — development + localhost only
        CsrfMiddleware::class,
    ];
}
