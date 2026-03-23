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

---