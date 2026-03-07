# luany

> Official application skeleton for the [Luany Framework](https://github.com/luany-ecosystem/luany-framework).

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![Framework](https://img.shields.io/badge/luany%2Fframework-v0.2-5B3171?style=flat-square)](https://packagist.org/packages/luany/framework)
[![License](https://img.shields.io/badge/license-MIT-E6874A?style=flat-square)](LICENSE)

---

## What is this?

This is the starting point for any Luany application. Clone it, run `php luany serve`, and you have a working MVC app with a full request lifecycle, AST template engine, CSRF protection, i18n, and a design system ready to build on.

It is intentionally minimal. No scaffolding. No generated code. Everything present has a purpose.

---

## Requirements

- PHP **8.1** or higher
- Composer

---

## Quick start

```bash
git clone https://github.com/luany-ecosystem/luany.git my-app
cd my-app
composer install
cp .env.example .env
php luany key:generate
php luany serve
```

Open `http://localhost:8000`.

---

## Structure

```
luany/
├── app/
│   ├── Controllers/
│   │   ├── Controller.php              # Base controller
│   │   ├── HomeController.php          # Example controller
│   │   └── Locale/
│   │       └── LocaleController.php    # Locale switching — sets cookie, redirects
│   ├── Exceptions/
│   │   └── Handler.php                 # Custom exception handler
│   ├── Http/
│   │   └── Kernel.php                  # HTTP kernel — global middleware stack
│   ├── Middleware/
│   │   ├── CsrfMiddleware.php          # CSRF token verification
│   │   └── LocaleMiddleware.php        # Locale detection (cookie → Accept-Language → env)
│   ├── Models/
│   │   └── .gitkeep
│   ├── Providers/
│   │   ├── AppServiceProvider.php      # App bindings, session, constants, helpers
│   │   └── DatabaseServiceProvider.php # Lazy PDO singleton, Model wiring
│   └── Support/
│       ├── Translator.php              # Lightweight i18n engine
│       └── helpers.php                 # Global helpers: url(), asset(), __(), locale()
│
├── bootstrap/
│   └── app.php                         # Application bootstrap
│
├── config/
│   ├── app.php                         # App name, env, locale, supported_locales
│   └── mail.php                        # Mail transport config
│
├── database/
│   └── migrations/
│       └── 2026_01_01_000000_create_users_table.php
│
├── lang/
│   ├── en.php                          # English translations
│   └── pt.php                          # Portuguese translations
│
├── luany-cli/
│   └── Commands/                       # CLI commands (php luany <command>)
│
├── public/
│   ├── index.php                       # Front controller
│   ├── .htaccess
│   └── assets/
│       ├── css/
│       │   ├── app.css                 # CSS entry point (@import chain)
│       │   ├── base.css                # Design system tokens + light mode layer
│       │   └── components/
│       │       └── buttons.css         # Global button styles
│       ├── images/
│       │   ├── icon/
│       │   └── logo/
│       └── js/
│           └── app.js
│
├── routes/
│   └── http.php                        # All HTTP routes
│
├── storage/
│   ├── cache/views/                    # LTE compiled template cache
│   └── logs/
│
├── views/
│   ├── components/
│   │   ├── flash.lte                   # Flash message bar (auto-dismiss)
│   │   ├── footer.lte
│   │   └── navbar.lte                  # Navbar with mobile menu, locale switcher, theme toggle
│   ├── layouts/
│   │   └── main.lte                    # Base HTML layout
│   └── pages/
│       ├── home/
│       │   ├── hero.lte
│       │   ├── playground.lte
│       │   ├── pipeline.lte
│       │   ├── features.lte
│       │   └── nextsteps.lte
│       ├── home.lte                    # Page orchestrator — @includes the sections above
│       └── errors/
│           ├── 404.lte
│           └── 500.lte
│
├── tests/
│
├── .env.example
├── .gitignore
├── CHANGELOG.md
├── composer.json
├── LICENSE
└── luany                               # CLI entry point
```

---

## LTE Template Engine

Luany uses **LTE** — a compiler that parses templates into an AST and emits optimised PHP. Zero regex. Deterministic output. Compiled views are cached in `storage/cache/views/` and auto-invalidated in debug mode.

Templates live in `views/` with the `.lte` extension.

### Syntax

```lte
{{-- Comment — stripped from compiled output --}}

{{ $variable }}        {{-- Escaped: htmlspecialchars() --}}
{!! $html !!}          {{-- Raw output — trusted HTML only --}}

@if($condition)
    ...
@elseif($other)
    ...
@else
    ...
@endif

@foreach($items as $item)
    <li>{{ $item }}</li>
@endforeach

@forelse($items as $i => $item)
    <li>{{ $i + 1 }}. {{ $item }}</li>
@empty
    <li>No items found.</li>
@endforelse

@php
    $computed = strtoupper($name);
@endphp

@csrf   {{-- Renders hidden CSRF token input --}}
```

### Layouts

```lte
{{-- views/layouts/main.lte --}}
<!DOCTYPE html>
<html lang="{{ locale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', env('APP_NAME', 'Luany'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
    @include('components.navbar')
    @include('components.flash')

    <main class="main-content">
        @yield('content')
    </main>

    @include('components.footer')

    @styles
    <script src="{{ asset('js/app.js') }}"></script>
    @scripts
    @stack('scripts')
</body>
</html>
```

`@styles` and `@scripts` are placed at the end of `<body>`. Component CSS (`@style` blocks) and JS (`@script` blocks) are extracted, deduplicated by hash, and injected there — not in `<head>`. Each block renders exactly once regardless of how many times a component is included.

```lte
{{-- views/pages/example.lte --}}
@extends('layouts.main')

@section('title', 'Example Page')

@section('content')
    <h1>{{ $title }}</h1>
@endsection

@style
    h1 { color: var(--luany-orange); }
@endstyle
```

---

## Routing

```php
// routes/http.php
use Luany\Core\Routing\Route;

Route::get('/',              [HomeController::class, 'index']);
Route::get('/posts',         [PostController::class, 'index']);
Route::get('/posts/{id}',    [PostController::class, 'show']);
Route::post('/posts',        [PostController::class, 'store']);
Route::put('/posts/{id}',    [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// Named routes
Route::get('/about', [PageController::class, 'about'])->name('about');

// Groups with shared middleware
Route::group(['middleware' => [AuthMiddleware::class]], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

---

## Controllers

```php
namespace App\Controllers;

use Luany\Core\Http\Request;

class PostController extends Controller
{
    public function show(Request $request, string $id): string
    {
        return view('pages.posts.show', [
            'post' => Post::find($id),
        ]);
    }
}
```

---

## Middleware

```php
namespace App\Middleware;

use Luany\Core\Http\Request;
use Luany\Core\Http\Response;
use Luany\Core\Middleware\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (!is_authenticated()) {
            return Response::make('', 302, ['Location' => '/login']);
        }

        return $next($request);
    }
}
```

Register globally in `app/Http/Kernel.php` or per-route in `routes/http.php`.

---

## Service Providers

```php
namespace App\Providers;

use Luany\Framework\Application;
use Luany\Framework\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(Application $app): void
    {
        $app->singleton('mailer', fn() => new Mailer(env('MAIL_HOST')));
    }

    public function boot(Application $app): void
    {
        // All providers registered — safe to resolve cross-provider deps here
    }
}
```

Two-phase lifecycle: all `register()` calls complete before any `boot()` runs.

---

## Exception handling

Customise `app/Exceptions/Handler.php` to control error responses:

```php
namespace App\Exceptions;

use Luany\Core\Http\Response;
use Luany\Framework\Exceptions\Handler as BaseHandler;

class Handler extends BaseHandler
{
    public function render(\Throwable $e): Response
    {
        if ($e instanceof ModelNotFoundException) {
            return Response::make(view('pages.errors.404'), 404);
        }

        return parent::render($e);
    }
}
```

With `APP_DEBUG=true`, a full-screen debug page shows the exception class, file, line, request info, and stack trace. In production (`APP_DEBUG=false`), the `500.lte` view is served instead.

---

## Internationalisation

Translation files live in `lang/{locale}.php` and return a flat associative array.

```php
// lang/en.php
return [
    'nav.home'       => 'Home',
    'welcome.title'  => 'Hello, :name',
];
```

Use the `__()` helper in any view or controller:

```lte
{{ __('nav.home') }}
{{ __('welcome.title', ['name' => $user['name']]) }}
```

Active locale is detected in this order: `app_locale` cookie → `Accept-Language` header → `APP_LOCALE` env → `en`.

Add a language:

1. Create `lang/{code}.php`
2. Add the code to `supported_locales` in `config/app.php`
3. Add a button in `views/components/navbar.lte`

---

## CLI

```bash
php luany serve                    # Development server at localhost:8000

php luany key:generate             # Generate APP_KEY in .env

php luany make:controller Name     # Scaffold a controller
php luany make:middleware Name     # Scaffold middleware
php luany make:model Name          # Scaffold a model
php luany make:provider Name       # Scaffold a service provider
php luany make:migration name      # Create a timestamped migration file

php luany migrate                  # Run pending migrations
php luany migrate:rollback         # Roll back last migration batch

php luany cache:clear              # Clear compiled view cache
```

---

## Environment

Copy `.env.example` to `.env`:

```ini
APP_NAME=Luany
APP_ENV=development
APP_DEBUG=true
APP_KEY=
APP_URL=http://localhost:8000
APP_LOCALE=en
APP_TIMEZONE=UTC
APP_HTTPS=false

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=luany
DB_USER=root
DB_PASS=
```

---

## Design system

The skeleton ships with a dark/light design system built on CSS custom properties. All tokens are in `public/assets/css/base.css`. Light mode is activated via `[data-theme="light"]` on `<html>` and persisted in `localStorage`.

Core palette:

| Token | Value | Role |
|-------|-------|------|
| `--luany-purple` | `#5B3171` | Brand primary |
| `--luany-orange` | `#E6874A` | Brand accent |
| `--luany-dark` | `#010213` | Background (dark) |
| `--luany-vibrant` | `#F2441D` | Danger / error |

The design system is **optional** — replace or remove it entirely. The framework has no opinion on CSS.

---

## Ecosystem

| Package | Role | Version |
|---------|------|---------|
| [luany/framework](https://packagist.org/packages/luany/framework) | Kernel, Handler, ServiceProvider, helpers | v0.2 |
| [luany/core](https://packagist.org/packages/luany/core) | Router, Request, Response, Middleware, Container | v0.2 |
| [luany/lte](https://packagist.org/packages/luany/lte) | AST template compiler | v0.2 |
| [luany/database](https://packagist.org/packages/luany/database) | Query builder, migrations, Model base | v0.1 |

---

## License

MIT — see [LICENSE](LICENSE).
