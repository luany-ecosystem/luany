<?php

/**
 * Application Helpers
 *
 * Loaded by AppServiceProvider::boot().
 * All functions use function_exists guards to prevent conflicts
 * with helpers already provided by luany/framework.
 *
 * Framework helpers available automatically (from luany/framework):
 *   app(), env(), base_path(), view(), redirect(), response(),
 *   config(), session(), csrf_token(), old(), abort(), validate(),
 *   __(), locale()
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

if (!function_exists('csrf_field')) {
    /**
     * Generate a hidden CSRF input field.
     * Uses the csrf_token() helper from luany/framework.
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

// ── Flash messages ────────────────────────────────────────────────────────────

if (!function_exists('flash')) {
    /**
     * Set a flash message for the next request.
     * Types: success | error | warning | info
     *
     * Uses the framework session service.
     */
    function flash(string $type, string $message): void
    {
        session()->flash('_flash', compact('type', 'message'));
    }
}

if (!function_exists('get_flash')) {
    /**
     * Retrieve and clear the current flash message.
     * Returns null if no flash is set.
     */
    function get_flash(): ?array
    {
        $flash = session()->get('_flash');
        if ($flash !== null) {
            session()->forget('_flash');
        }
        return $flash;
    }
}

// ── Auth helpers ──────────────────────────────────────────────────────────────

if (!function_exists('auth_user')) {
    function auth_user(): ?int
    {
        $id = session()->get('user_id');
        return $id !== null ? (int) $id : null;
    }
}

if (!function_exists('is_authenticated')) {
    function is_authenticated(): bool
    {
        return auth_user() !== null;
    }
}
