<?php

namespace App\Http\Middleware;

use Luany\Core\Http\Request;
use Luany\Core\Http\Response;
use Luany\Core\Middleware\MiddlewareInterface;

/**
 * DevMiddleware
 *
 * Active ONLY when APP_ENV=development. Zero overhead in production —
 * the first line of handle() is an environment check that returns early.
 *
 * Two responsibilities:
 *
 *   1. Serve /__luany_dev/client.js
 *      The LDE browser client is bundled inside luany/cli. This middleware
 *      intercepts the URI and serves the file directly, without registering
 *      a route (routes are production concerns — dev tooling is not).
 *
 *   2. Inject <script src="/__luany_dev/client.js"></script>
 *      Appended before </body> in every text/html response. The injected
 *      script connects to the WebSocket server started by `luany dev` and
 *      applies live reload / CSS inject on file changes.
 *
 * Placement in Kernel::$middleware:
 *   DevMiddleware MUST be the FIRST entry so it wraps the entire pipeline
 *   and receives the final, fully-rendered Response before send().
 *
 * Why here and not in Engine.php or Kernel::boot():
 *   - Engine.php must remain pure — no environment or dev concerns.
 *   - Kernel::boot() runs before routing — no Response available.
 *   - Middleware is the correct layer: it sits between request and response,
 *     has access to both, and is trivially skipped in production.
 */
class DevMiddleware implements MiddlewareInterface
{
    /**
     * URI intercepted to serve the browser client script.
     * Must match the <script src> injected below — keep in sync.
     */
    private const CLIENT_URI = '/__luany_dev/client.js';

    public function handle(Request $request, callable $next): Response
    {
        // ── Production fast-path ───────────────────────────────────────────────
        // Check all possible sources the Luany framework may use to populate
        // env vars from .env (putenv → getenv, $_ENV, $_SERVER).
        if (!$this->isDevelopment()) {
            return $next($request);
        }

        // ── Serve client.js ───────────────────────────────────────────────────
        // Intercept BEFORE the router — this URI is never a registered route.
        if ($request->uri() === self::CLIENT_URI) {
            return $this->serveClientScript();
        }

        // ── Run the full pipeline ─────────────────────────────────────────────
        $response = $next($request);

        // ── Inject script tag ─────────────────────────────────────────────────
        return $this->injectClientScript($response);
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    /**
     * Resolve APP_ENV from all possible sources.
     *
     * The Luany framework's Env::load() may populate:
     *   - putenv()   → readable by getenv()
     *   - $_ENV      → readable by $_ENV[]
     *   - $_SERVER   → readable by $_SERVER[]
     *
     * Checking all three guarantees we detect development mode regardless
     * of the php.ini variables_order configuration.
     */
    private function isDevelopment(): bool
    {
        $env = $_ENV['APP_ENV']
            ?? $_SERVER['APP_ENV']
            ?? getenv('APP_ENV')
            ?? 'production';

        return $env === 'development';
    }

    /**
     * Serve the LDE browser client JavaScript file.
     *
     * Searches two locations:
     *   1. vendor/luany/cli/...  — normal composer install
     *   2. ../../luany-cli/...   — monorepo / local development of Luany itself
     *
     * Returns 200 with the file content, or a 404 JS comment if not found.
     */
    private function serveClientScript(): Response
    {
        $paths = [
            // 1. Vendor — normal composer install / composer global require
            base_path('/vendor/luany/cli/src/Resources/dev/client.js'),

            // 2. Monorepo — local development of the Luany ecosystem itself
            dirname(base_path(), 2) . '/luany-cli/src/Resources/dev/client.js',
        ];

        foreach ($paths as $fullPath) {
            if (file_exists($fullPath)) {
                return Response::make(file_get_contents($fullPath), 200)
                    ->header('Content-Type', 'application/javascript; charset=UTF-8')
                    ->header('Cache-Control', 'no-store');
            }
        }

        return Response::make(
            '// LDE client not found. Run: composer require --dev luany/cli',
            404
        )->header('Content-Type', 'application/javascript; charset=UTF-8');
    }

    /**
     * Inject the LDE client <script> tag before </body>.
     *
     * Injects two tags in order:
     *   1. Inline <script> that sets window.__LDE_WS_PORT__ — must come
     *      first so client.js reads the correct port on load.
     *   2. <script src="/__luany_dev/client.js"> — the actual client.
     *
     * Only injects when:
     *   - Response is 2xx
     *   - Content-Type contains text/html
     *   - Body contains </body>
     *
     * Skips redirects, JSON, error pages, and any non-HTML response.
     */
    private function injectClientScript(Response $response): Response
    {
        $status      = $response->getStatusCode();
        $headers     = array_change_key_case($response->getHeaders(), CASE_LOWER);
        $contentType = $headers['content-type'] ?? '';

        if ($status < 200 || $status >= 300) {
            return $response;
        }

        // Inject when Content-Type is text/html OR absent.
        // Controllers returning view() produce Response::make($html, 200)
        // without an explicit Content-Type — the framework does not default
        // to text/html in the Response object, only at send() time via PHP.
        // We must NOT inject into JSON, redirects, JS, CSS, or binary responses.
        $isExplicitNonHtml = $contentType !== ''
            && stripos($contentType, 'text/html') === false;

        if ($isExplicitNonHtml) {
            return $response;
        }

        $body = $response->getBody();

        if (stripos($body, '</body>') === false) {
            return $response;
        }

        $wsPort = (int) (getenv('LDE_WS_PORT') ?: 35729);

        // Order matters: port config must be defined before client.js executes.
        $inject  = '<script>window.__LDE_WS_PORT__ = ' . $wsPort . ';</script>';
        $inject .= '<script src="' . self::CLIENT_URI . '"></script>';

        $body = str_ireplace('</body>', $inject . '</body>', $body);

        return $response->body($body);
    }
}