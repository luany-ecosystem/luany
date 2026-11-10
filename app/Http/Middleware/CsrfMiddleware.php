<?php

namespace App\Http\Middleware;

use Luany\Framework\Http\Middleware\CsrfMiddleware as BaseCsrfMiddleware;

/**
 * CsrfMiddleware
 *
 * Application CSRF protection, backed by luany/framework.
 *
 * Every POST / PUT / PATCH / DELETE request must carry the session token in
 * one of:
 *   - the "_token" form field      → @csrf  or  {!! csrf_field() !!}
 *   - the "X-CSRF-TOKEN" header    → AJAX, read from <meta name="csrf-token">
 *
 * The token lives in the session ("_csrf_token") and stays the same for the
 * whole session, so multiple tabs and concurrent requests keep working.
 * Regenerate it on login / logout:
 *
 *   session()->regenerate();
 *   app('csrf')->regenerate();
 */
class CsrfMiddleware extends BaseCsrfMiddleware
{
    /**
     * URI patterns excluded from verification (e.g. third-party webhooks).
     * Supports a trailing "*" wildcard: '/webhooks/*'.
     *
     * @var array<int, string>
     */
    protected array $except = [];

    /** 419 "Page Expired" — the conventional status for a CSRF mismatch. */
    protected int $failureStatus = 419;
}
