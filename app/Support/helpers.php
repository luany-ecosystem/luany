<?php

/**
 * Application Helpers
 *
 * Loaded by AppServiceProvider::boot().
 * All functions use function_exists guards to prevent conflicts.
 *
 * Framework helpers already available (from luany/framework):
 *   app(), env(), base_path(), view(), redirect(), response()
 */

// ── URL helpers ───────────────────────────────────────────────────────────────

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $base = rtrim(defined('BASE_URL') ? BASE_URL : env('APP_URL', '/'), '/');
        $path = '/' . ltrim($path, '/');
        return $base . ($path === '/' ? '' : $path);
    }
}

if (!function_exists('route')) {
    function route(string $name, array $params = []): string
    {
        $uri = \Luany\Core\Routing\Route::getRouter()->getNamedRoute($name, $params);

        if ($uri === null) {
            throw new \RuntimeException("Named route [{$name}] is not defined.");
        }

        return url($uri);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $base = rtrim(defined('ASSETS_URL') ? ASSETS_URL : url('assets'), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

// ── Security helpers ──────────────────────────────────────────────────────────

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

// ── Flash messages ────────────────────────────────────────────────────────────

if (!function_exists('flash')) {
    /**
     * Set a flash message for the next request.
     * Types: success | error | warning | info
     */
    function flash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['_flash'] = compact('type', 'message');
    }
}

if (!function_exists('get_flash')) {
    /**
     * Retrieve and clear the current flash message.
     * Returns null if no flash is set.
     */
    function get_flash(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $flash = $_SESSION['_flash'] ?? null;
        unset($_SESSION['_flash']);
        return $flash;
    }
}

// ── Auth helpers ──────────────────────────────────────────────────────────────

if (!function_exists('auth_user')) {
    function auth_user(): ?int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }
}

if (!function_exists('is_authenticated')) {
    function is_authenticated(): bool
    {
        return auth_user() !== null;
    }
}

if (!function_exists('abort')) {
    function abort(int $status, string $message = ''): never
    {
        http_response_code($status);
        echo $message;
        exit;
    }
}

if (!function_exists('__')) {
    /**
     * Translate a key using the active locale.
     *
     * Usage:
     *   __('nav.home')
     *   __('footer.copyright', ['year' => date('Y'), 'name' => 'Luany'])
     *
     * Returns the key itself when no translation is found —
     * pages never silently display empty strings.
     */
    function __(string $key, array $replace = []): string
    {
        return app('translator')->get($key, $replace);
    }
}

if (!function_exists('locale')) {
    /**
     * Return the currently active locale code.
     *
     * Usage:
     *   locale()           → 'en' | 'pt'
     *   locale() === 'pt'  → true
     */
    function locale(): string
    {
        return app('translator')->getLocale();
    }
}