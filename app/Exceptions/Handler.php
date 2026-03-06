<?php

namespace App\Exceptions;

use Luany\Core\Http\Response;
use Luany\Framework\Exceptions\Handler as BaseHandler;

/**
 * Application Exception Handler
 *
 * Override report() to send errors to external services (Sentry, etc.).
 * Override render() to return custom error views per exception type.
 *
 * Example — custom 404 view in production:
 *   public function render(\Throwable $e): Response
 *   {
 *       if ($e instanceof \Luany\Core\Exceptions\RouteNotFoundException) {
 *           return Response::make(view('pages.errors.404'), 404);
 *       }
 *       return parent::render($e);
 *   }
 */
class Handler extends BaseHandler
{
    /**
     * Exception types that should never be written to logs.
     */
    protected array $dontReport = [
        // \Luany\Core\Exceptions\RouteNotFoundException::class,
    ];

    public function report(\Throwable $e): void
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return;
            }
        }

        parent::report($e);
    }

    public function render(\Throwable $e): Response
    {
        // 404 — always show the styled view
        if ($e instanceof \Luany\Core\Exceptions\RouteNotFoundException) {
            return Response::make(view('pages.errors.404'), 404);
        }

        // 500 — styled view in production, framework debug page in development
        if (!$this->debug) {
            return Response::make(view('pages.errors.500'), 500);
        }

        return parent::render($e);
    }
}