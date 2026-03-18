<?php

namespace App\Http\Middleware;

use Luany\Core\Http\Request;
use Luany\Core\Http\Response;
use Luany\Core\Middleware\MiddlewareInterface;

/**
 * CsrfMiddleware
 *
 * Automatic CSRF protection for all state-changing requests.
 * GET, HEAD and OPTIONS are always allowed.
 * POST, PUT, PATCH, DELETE require a valid _token or X-CSRF-Token header.
 *
 * LTE usage — add inside any form:
 *   @csrf
 *
 * AJAX usage:
 *   fetch('/users', {
 *       method: 'POST',
 *       headers: { 'X-CSRF-Token': window.csrfToken }
 *   });
 *
 * Exempt a route (webhooks, etc.):
 *   private array $except = ['/webhook/stripe'];
 */
class CsrfMiddleware implements MiddlewareInterface
{
    private array $except = [];

    public function handle(Request $request, callable $next): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if ($this->isReading($request) || $this->inExceptList($request)) {
            return $next($request);
        }

        if (!$this->tokensMatch($request)) {
            return Response::make($this->forbiddenPage(), 419)
                ->header('Content-Type', 'text/html; charset=UTF-8');
        }

        $this->regenerateToken();

        return $next($request);
    }

    private function isReading(Request $request): bool
    {
        return in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);
    }

    private function inExceptList(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->uri() === $pattern || fnmatch($pattern, $request->uri())) {
                return true;
            }
        }
        return false;
    }

    private function tokensMatch(Request $request): bool
    {
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $inputToken   = $request->input('csrf_token', '')
                    ?: $request->header('X-CSRF-Token', '');

        if (empty($sessionToken) || empty($inputToken)) {
            return false;
        }

        return hash_equals($sessionToken, (string) $inputToken);
    }

    private function regenerateToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    private function forbiddenPage(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Page Expired</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700;800&family=DM+Sans:wght@400;500&display=swap');
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #010213;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card {
            background: #0D0D26;
            border: 1px solid rgba(91, 49, 113, .35);
            border-radius: 20px;
            padding: 4rem 3rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 16px 48px rgba(0,0,0,.6);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(135deg, #5B3171 0%, #E6874A 100%);
        }
        .icon { font-size: 3rem; margin-bottom: 1.5rem; display: block; }
        h1 {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.25rem;
            font-weight: 800;
            color: rgba(255,255,255,.95);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .75rem;
        }
        p {
            color: rgba(255,255,255,.55);
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            padding: .75rem 2rem;
            background: linear-gradient(135deg, #5B3171, #E6874A);
            color: #fff;
            border-radius: 4px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="icon">⏰</span>
        <h1>Page Expired</h1>
        <p>Your session has expired or the request token was invalid.<br>Please go back and try again.</p>
        <button class="btn" onclick="history.back()">← Go Back</button>
    </div>
</body>
</html>
HTML;
    }
}