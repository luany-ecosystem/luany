# Upgrade Guide — v0.x → v1.0

This guide covers every breaking change and action required when upgrading
an application built on Luany v0.x to v1.0.

---

## Overview of Breaking Changes

| Area | What changed | Action required |
|---|---|---|
| `abort()` | Moved to `luany/framework` | Remove custom helper if you defined one |
| `csrf_token()` | Session key corrected | Remove custom override if you had one |
| Session bootstrap | Kernel manages session | Remove manual `session_start()` |
| Route files | Auto-discovery enabled | No action — backward compatible |
| Feature routes | `make:feature` generates per-file | Update generator usage |
| `validate()` | New framework helper | Optionally refactor controllers |

---

## Breaking Changes

---

### `abort()` — moved to framework

**Before:**
```php
// app/Support/helpers.php (custom implementation)
function abort(int $status, string $message = ''): never
{
    http_response_code($status);
    echo $message;
    exit;
}
```

**Now:**
```php
// luany/framework helpers.php — available automatically
function abort(int $code, string $message = ''): never
{
    throw new HttpException($code, $message);
}
```

**Action:** Remove any custom `abort()` definition from `app/Support/helpers.php`. The framework version is loaded first via Composer autoload. The `function_exists` guard prevents conflicts, but the old implementation bypassed the response lifecycle (`exit` vs `throw`).

---

### `csrf_token()` — session key corrected

**Before (broken):**
```php
// app/Support/helpers.php — used wrong session key
$_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
```

**Now (correct):**
```php
// luany/framework — uses CsrfToken service
// Key in session: 'csrf_token' (no underscore prefix)
// CsrfMiddleware validates: $_SESSION['csrf_token']
```

**Action:** Remove the custom `csrf_token()` from `app/Support/helpers.php`. The framework's implementation uses the correct session key that `CsrfMiddleware` validates against. If you had forms that were silently failing CSRF validation, this fix resolves it.

---

### Session bootstrap — Kernel manages the session

**Before:**
```php
// app/Providers/AppServiceProvider.php
public function boot(Application $app): void
{
    $this->startSession(); // ← was calling session_start() manually
    ...
}

private function startSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([...]);
        session_start();
    }
}
```

**Now:**
```php
// Kernel::boot() → registerSession() → FileSession::start()
// Session is started once, via FileSession, before providers boot.
```

**Action:** Remove `startSession()` from `AppServiceProvider` and any explicit `session_start()` calls in providers or bootstrap. `Kernel::registerSession()` handles this before `bootProviders()` is called.

---

### Route files — auto-discovery (non-breaking, but important)

**Before:**
```php
// Kernel loaded only routes/http.php
protected string $routesFile = 'routes/http.php';
```

**Now:**
```php
// Kernel loads routes/http.php first, then all other *.php alphabetically
// routes/http.php   ← loaded first
// routes/api.php    ← auto-discovered
// routes/users.php  ← auto-discovered
```

**Action:** No action required — `routes/http.php` is still loaded first. This is fully backward compatible. Optionally split large route files into per-feature files; the Kernel will discover them automatically.

---

### `make:feature` — generates per-feature route files

**Before:**
```bash
luany make:feature Product
# → appended Route::resource() to routes/http.php
```

**Now:**
```bash
luany make:feature Product
# → generates routes/products.php (dedicated file)
# → Kernel auto-discovers it
```

**Action:** If you ran `make:feature` before this change and already have routes in `routes/http.php`, no action is needed — those routes still work. For new features, the CLI generates clean isolated files.

---

## New Features

These are additive — no migration required.

---

### `validate()` helper — replaces manual Validator boilerplate

**Before:**
```php
public function store(Request $request): Response
{
    $v = Validator::make($request->all(), [
        'name'  => 'required|string|min:2',
        'email' => 'required|email',
    ]);

    if ($v->fails()) {
        session()->flash('errors', $v->errors());
        session()->flash('_old_input', $request->all());
        return redirect('/users/create');
    }

    $data = $v->validated();
    User::create($data);
    return redirect('/users');
}
```

**Now:**
```php
public function store(Request $request): Response
{
    $data = validate($request->body(), [
        'name'  => 'required|string|min:2',
        'email' => 'required|email',
    ], '/users/create');

    User::create($data);
    return redirect('/users');
}
```

`validate()` flashes errors and `_old_input` automatically, then throws `ValidationException` which the Kernel converts to a redirect. Controllers are clean.

---

### `Request::body()` — explicit body access

```php
// Before: $request->all() merged body + query string
// Now: $request->body() returns body fields only

$data = validate($request->body(), $rules, '/back');
// Safe: query string parameters are not mixed into validated data
```

---

### `abort()` — clean HTTP errors

```php
$product = Product::find($id);

if (!$product) {
    abort(404);           // → 404 Not Found
}

if (!$user->isAdmin()) {
    abort(403);           // → 403 Forbidden
}

abort(422, 'Custom message');
```

---

### New CLI commands

```bash
luany make:request StoreProductRequest   # form request class
luany make:test ProductControllerTest    # PHPUnit test class
luany route:list                         # display all registered routes
```

---

## Package Version Requirements

Update `composer.json` in your application:

```json
{
    "require": {
        "luany/framework": "^1.0",
        "luany/database":  "^1.0"
    }
}
```

Then run:

```bash
composer update
```

---

## Upgrade Checklist

```
[ ] Remove custom abort() from app/Support/helpers.php
[ ] Remove custom csrf_token() from app/Support/helpers.php
[ ] Remove startSession() from AppServiceProvider
[ ] Update composer.json constraints to ^1.0
[ ] Run: composer update
[ ] Run: ./vendor/bin/phpunit --no-coverage
[ ] Test a form submission (CSRF + validation)
[ ] Optionally split routes/http.php into per-feature files
```