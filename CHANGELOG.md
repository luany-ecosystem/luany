# Changelog — luany/luany

All notable changes to this application skeleton are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
Versioning follows [Semantic Versioning](https://semver.org/).

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