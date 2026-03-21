# Changelog — luany/luany

All notable changes to this application skeleton are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
Versioning follows [Semantic Versioning](https://semver.org/).

---

## [Unreleased] — next/v1

### Changed
- `composer.json` — updated constraints: `luany/framework ^0.3 → ^0.4`, `luany/database ^0.1 → ^0.3`.
- `app/Support/helpers.php` — `flash()` and `get_flash()` now use `session()` framework service instead of `$_SESSION` directly. `auth_user()` uses `session()->get()`. Consistent with framework session abstraction.
- `app/Providers/AppServiceProvider` — removed `startSession()`. Session lifecycle is managed by `Kernel::registerSession()` via `FileSession`. Calling `session_start()` twice caused conflicts.
- `lang/en.php` and `lang/pt.php` — updated `hero.eyebrow` to `v1.0`, `hero.stat_tests` to `252`.

### Fixed
- `app/Support/helpers.php` — removed duplicate `csrf_token()` that used `$_SESSION['_csrf_token']`. The framework's `csrf_token()` uses `$_SESSION['csrf_token']` (via `CsrfToken` service) and the `CsrfMiddleware` validates against `csrf_token` — the skeleton's version used a different key, causing CSRF validation to always fail on form submissions.
- `app/Support/helpers.php` — removed duplicate `abort()` that used `http_response_code() + echo + exit`. The framework's `abort()` throws `HttpException`, which is caught by the Kernel and converted to a proper `Response`. The old implementation bypassed the response lifecycle entirely.
- `views/pages/errors/404.lte` and `views/pages/errors/500.lte` — converted `<?php echo htmlspecialchars(...) ?>` raw PHP to LTE syntax (`{{ }}` for escaped output, `{!! !!}` for raw). Views are rendered through the LTE engine and should use LTE syntax throughout.

### Breaking Changes
- None for end users. All changes fix internal inconsistencies between the skeleton and the framework packages.

---

## [0.2.0] — Initial skeleton

### Added
- `public/index.php` — single entry point. Bootstrap → Kernel boot → handle request → send → terminate.
- `bootstrap/app.php` — application bootstrap. Loads autoloader, creates `Application`, loads `.env`, registers service providers, binds custom `Kernel` and `Handler`.
- `app/Http/Kernel.php` — extends `BaseKernel` with `LocaleMiddleware` and `CsrfMiddleware` as global middleware.
- `app/Http/Middleware/CsrfMiddleware.php` — CSRF protection with `$except` list, 419 Page Expired styled response.
- `app/Http/Middleware/LocaleMiddleware.php` — extends framework `LocaleMiddleware`.
- `app/Controllers/Controller.php` — abstract base controller.
- `app/Controllers/HomeController.php` — renders home view with demo data.
- `app/Controllers/Locale/LocaleController.php` — handles `GET /locale/{lang}`, sets cookie, redirects.
- `app/Exceptions/Handler.php` — extends `BaseHandler`. Renders `pages.errors.404` on `RouteNotFoundException`, `pages.errors.500` in production. Debug page in development.
- `app/Providers/AppServiceProvider.php` — registers `Translator`, configures timezone, defines URL/path constants, loads application helpers.
- `app/Providers/DatabaseServiceProvider.php` — registers `Connection` as lazy singleton, wires into `Model::setConnection()`.
- `config/app.php` — application name, env, debug, URL, locale settings.
- `config/mail.php` — mail configuration stub.
- `routes/http.php` — home route and locale switching route.
- `views/layouts/main.lte` — base layout with navbar, flash, main content, footer, `@styles`, `@scripts`.
- `views/components/navbar.lte` — sticky navbar with dark/light theme toggle, locale switcher.
- `views/components/footer.lte` — footer with links and attribution.
- `views/components/flash.lte` — flash message bar with auto-dismiss and type colours.
- `views/pages/home.lte` — extends layout, includes hero, playground, pipeline, features, nextsteps sections.
- `views/pages/errors/404.lte` and `500.lte` — standalone error views (no layout dependency for resilience). Full design system with animations, dark/light theme support.
- `lang/en.php` and `lang/pt.php` — complete translations for all UI strings.
- `.env.example` — complete environment configuration template.
- `public/assets/` — CSS design system (`base.css`, `app.css`, component styles), JS (`app.js` with theme toggle), SVG logo and icon.