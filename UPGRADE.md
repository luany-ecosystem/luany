# Upgrade Guide — luany/luany

---

## v1.0.x → v1.1.0

### What changed

BrowserSync has been replaced by the **Luany Dev Engine (LDE)**. The proxy
layer has been removed — the browser now connects directly to PHP.

### Breaking Changes

- `npm run dev` (BrowserSync) replaced by `luany dev`
- Node.js is now required for live reload
- `APP_ENV=development` must be set in `.env` for `DevMiddleware` to activate

### Migration steps

**1. Update dependencies:**
```bash
composer update luany/framework luany/database
npm install
```

**2. Add `DevMiddleware` to your Kernel** (new projects have this automatically):
```php
// app/Http/Kernel.php
protected array $middleware = [
    DevMiddleware::class,    // FIRST — wraps the full pipeline
    LocaleMiddleware::class,
    CsrfMiddleware::class,
];
```

**3. Set environment:**
```
APP_ENV=development
```

**4. Replace your dev command:**
```bash
# Before
npm run dev

# After
luany dev
```

`DevMiddleware` is a zero-cost passthrough in production — no need to remove it before deploying.

### Upgrade checklist

- [ ] Run `npm install`
- [ ] Set `APP_ENV=development` in `.env`
- [ ] Add `DevMiddleware::class` as first entry in `Kernel::$middleware`
- [ ] Replace `npm run dev` with `luany dev` in your workflow
- [ ] Run `./vendor/bin/phpunit --no-coverage` to verify nothing broke

---

## v0.x → v1.0

### Requirements

- PHP 8.2+

### Breaking Changes

- PHP 8.1 is no longer supported
- Internal routing loading is now auto-discovered (no `$routesFile` override needed)

### Migration steps

**1. Update `composer.json`:**
```json
{
  "require": {
    "php": ">=8.2",
    "luany/framework": "^1.0",
    "luany/database": "^1.0"
  }
}
```

**2. Run:**
```bash
composer update
```

### Upgrade checklist

- [ ] Ensure PHP 8.2+ is installed
- [ ] Update `composer.json` constraints to `^1.0`
- [ ] Run `composer update`
- [ ] Remove custom `abort()` from `app/Support/helpers.php` (now in framework)
- [ ] Remove custom `csrf_token()` from `app/Support/helpers.php` (now in framework)
- [ ] Remove `startSession()` from `AppServiceProvider` (now managed by Kernel)
- [ ] Optionally split `routes/http.php` into per-feature files
- [ ] Run `./vendor/bin/phpunit --no-coverage`
- [ ] Test a form submission (CSRF + validation)