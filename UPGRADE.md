# Upgrade Guide — v0.x → v1.0

This guide covers breaking changes and required steps to upgrade
an application from Luany v0.x to v1.0.

---

## Requirements

- PHP 8.2+

---

## Steps

- [ ] Ensure PHP 8.2+ is installed

- [ ] Update composer.json:

```json
{
  "require": {
    "php": ">=8.2",
    "luany/framework": "^1.0",
    "luany/database": "^1.0"
  }
}
```

- [ ] Run: composer update
- [ ] Run: npm install (optional, for live reload)

## Breaking Changes

PHP 8.1 is no longer supported

Internal routing loading is now auto-discovered (no $routesFile override)

## Upgrade Checklist

[ ] Remove custom abort() from app/Support/helpers.php
[ ] Remove custom csrf_token() from app/Support/helpers.php
[ ] Remove startSession() from AppServiceProvider
[ ] Update composer.json constraints to ^1.0
[ ] Run: composer update
[ ] Run: ./vendor/bin/phpunit --no-coverage
[ ] Test a form submission (CSRF + validation)
[ ] Optionally split routes/http.php into per-feature files
[ ] Update php constraint to >=8.2 in composer.json
[ ] Run: npm install (for live reload support)
[ ] Ensure PHP 8.2+ is installed

## Development Server — npm run dev → luany dev

BrowserSync has been replaced by the Luany Dev Engine (LDE). The proxy
layer has been removed — the browser now connects directly to PHP.

**Install Node.js dependencies:**
```bash
npm install
```

**Start the dev server:**
```bash
# Before (BrowserSync)
npm run dev

# After (LDE)
luany dev
```

**Update `.env`:**
```
APP_ENV=development
```

**Register `DevMiddleware` in `app/Http/Kernel.php`:**
```php
protected array $middleware = [
    DevMiddleware::class,    // FIRST — wraps the full pipeline
    LocaleMiddleware::class,
    CsrfMiddleware::class,
];
```

`DevMiddleware` is a zero-cost passthrough in production — no need to
remove it before deploying.

- [ ] Run: `npm install`
- [ ] Set `APP_ENV=development` in `.env`
- [ ] Add `DevMiddleware::class` as first entry in `Kernel::$middleware`
- [ ] Replace `npm run dev` with `luany dev` in your workflow

---