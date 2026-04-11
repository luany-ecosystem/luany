# Changelog — luany/luany

All notable changes to this application skeleton are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
Versioning follows [Semantic Versioning](https://semver.org/).

---

## [1.1.2] — 2026-04-11

### Added

- `database/seeders/DatabaseSeeder.php` — entry point for all seeders. Calls `UserSeeder` via `$this->call()`. Run with `luany db:seed`.
- `database/seeders/UserSeeder.php` — example seeder corresponding to the default `users` table. Inserts two example records using `INSERT IGNORE` (safe to re-run).
- `app/Models/User.php` — Active Record model corresponding to the default `users` migration. `$fillable = ['name', 'email']`. No auth assumptions.

### Changed

- `database/migrations/2026_01_01_000000_create_users_table.php` — removed `password` and `updated_at` columns. The default skeleton does not include authentication. Table now has only `id`, `name`, `email`, `created_at` — a clean, honest example that matches `User.php`.

## [1.1.1] - 2026-03-29

### Fixed
- Fix DevMiddleware failing to locate LDE client script by resolving path via `LDE_CLIENT_PATH` environment variable.

## [1.1.0] — 2026-03-28

### Added
- `app/Http/Middleware/DevMiddleware.php` — development-only middleware. Zero-cost passthrough in production (`APP_ENV !== development` fast-path). Two responsibilities:
  1. Serves `/__luany_dev/client.js` directly (no route registration required).
  2. Injects `<script src="/__luany_dev/client.js" defer></script>` before `</body>` in every 2xx HTML response.
  Only injects into `text/html` responses — JSON, redirects, downloads are never touched.
- LDE feature card added to home page — showcases live reload capability to new developers.
- Feature scaffolding (`make:feature`) highlighted in home page features and next steps sections.

### Changed
- `app/Http/Kernel.php` — `DevMiddleware` registered as the **first** global middleware (outermost position), ensuring it wraps the complete pipeline and receives the final rendered `Response` before `send()`.
- `package.json` — replaced BrowserSync with Luany Dev Engine:
  - `"dev"` script: `"luany dev"` (previously `concurrently + browser-sync --proxy`)
  - `devDependencies`: `chokidar ^3.6.0` + `ws ^8.18.0` (previously `browser-sync ^3.0.0` + `concurrently ^9.0.0`)
- Development architecture: browser now connects directly to PHP on port 8000 — no proxy layer. Live reload signals delivered via WebSocket on port 35729. CSS changes inject without page reload; PHP/LTE/JS changes trigger a clean full reload.
- Home page "Next steps" section updated — `luany dev` added as the development server command.
- Home page features section expanded from 4 to 6 cards (Dev Engine + Feature Scaffolding added).
- Page title handling simplified in `views/pages/home.lte` — removed redundant `$title ??` fallback.

### Fixed
- `hero.stat_tests` translation key was returning a numeric value instead of a label, causing `63 252` to render in the hero stats. Corrected to return `"tests"` / `"testes"`.
- Hero test count updated from `63` to `176` to reflect the current test suite.
- `hero.eyebrow` translation exposed `APP_ENV=development` in production UI. Replaced with `"v1.0 — Open source · MIT License"`.
- Various EN/PT translation inconsistencies corrected.

### Removed
- BrowserSync-based development workflow (`npm run dev` with `--proxy localhost:8000`).
- `browser-sync ^3.0.0` and `concurrently ^9.0.0` from `devDependencies`.
- Proxy layer between browser and PHP server — eliminated request loops, port conflicts (3000/8000), and session state corruption.

### Breaking Changes
- `npm run dev` (BrowserSync) replaced by `luany dev` (Luany Dev Engine).
- Node.js is now required for live reload (`chokidar` and `ws` via `npm install`).
- `APP_ENV=development` must be set in `.env` for `DevMiddleware` to activate.

---

## [1.0.0] — 2026-03-22

### Added
- Live reload development workflow via BrowserSync (`npm run dev`)
- Flash messaging helpers: `flash()` and `get_flash()`
- Flash component with animation and improved UI
- Basic CI workflow for installation and autoload validation

### Changed
- Minimum PHP version raised to 8.2
- Upgraded Luany core dependencies to stable 1.0 releases
- Improved README with development workflow and CLI commands
- Simplified route loading via auto-discovery

### Breaking Changes
- PHP 8.1 is no longer supported

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