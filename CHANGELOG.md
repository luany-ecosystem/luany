# Changelog

All notable changes to the **luany skeleton** are documented here.

Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).
Versioning follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

---

## [0.1.2] — 2026-03-07

### Added
- Light mode — `[data-theme="light"]` token layer in `base.css`, anti-FOUC script in `main.lte`, theme toggle in navbar with `localStorage` persistence
- Home componentized — sections split into `views/pages/home/` (hero, playground, pipeline, features, nextsteps)
- `nextsteps.lte` — CLI reference section replacing the generic CTA
- `theme.to_light` / `theme.to_dark` translation keys in `lang/en.php` and `lang/pt.php`

### Changed
- Hero copy updated — speaks to developers who already have the skeleton running
- README rewritten — minimal skeleton README following industry standard

### Fixed
- `<html lang>` attribute now reflects the active locale dynamically (`locale()`) in `main.lte`, `404.lte`, and `500.lte` — previously hardcoded to `"en"`
- `APP_LOCALE` and `APP_HTTPS` added to `.env.example` — both are read by the i18n middleware and locale controller but were undocumented
- DB variable names in README corrected to match `.env.example` (`DB_NAME`, `DB_USER`, `DB_PASS`)

---

## [0.1.1] — 2026-03-07

### Added
- Internationalisation system — `Translator`, `LocaleMiddleware`, `LocaleController`
- `lang/en.php` and `lang/pt.php` — full EN/PT translations for all skeleton views
- `config/app.php` — centralised app config with `locale`, `fallback_locale`, `supported_locales`
- `__()` and `locale()` helpers in `app/Support/helpers.php`
- EN | PT locale switcher in navbar with `aria-pressed` state
- `GET /locale/{lang}` route — sets `app_locale` cookie (1 year) and redirects back
- `.gitattributes` — enforces LF line endings across platforms

### Changed
- `AppServiceProvider::register()` now binds the `Translator` singleton reading from `config/app.php`
- `Kernel.php` — `LocaleMiddleware` added before `CsrfMiddleware` in global stack

---

## [0.1.0] — 2026-03-07

### Added
- Initial skeleton release
- Full MVC lifecycle: `bootstrap → boot → handle → send → terminate`
- `AppServiceProvider` — timezone, session, URL constants, helper loader
- `DatabaseServiceProvider` — lazy PDO singleton, `Model::setConnection` wiring
- `CsrfMiddleware` — timing-safe `hash_equals` token verification, `forbiddenPage()` 419 response
- `Handler` — `$dontReport`, `report()`, `render()` with 404/500 view delegation
- LTE playground home page — 3-tab interactive demo (foreach, escaping, compiled PHP)
- Self-contained `404.lte` and `500.lte` — inline CSS, zero external asset dependency
- Navbar with mobile hamburger menu, GitHub pill, locale switcher
- Footer with 4-column grid, ecosystem package links with version badges
- Flash message bar — auto-dismiss after 5 seconds with animated exit
- Design system — `base.css` with brand tokens, `buttons.css` with 5 variants
- `app.js` — exposes `window.csrfToken` from meta tag for AJAX use
- CLI: `serve`, `key:generate`, `cache:clear`, `make:*` (controller/model/migration/middleware/provider), `migrate`, `migrate:rollback`
- `composer.json` `post-create-project-cmd` — auto-copies `.env` and generates key on `composer create-project`

[Unreleased]: https://github.com/luany-ecosystem/luany/compare/v0.1.2...HEAD
[0.1.2]: https://github.com/luany-ecosystem/luany/compare/v0.1.1...v0.1.2
[0.1.1]: https://github.com/luany-ecosystem/luany/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/luany-ecosystem/luany/releases/tag/v0.1.0