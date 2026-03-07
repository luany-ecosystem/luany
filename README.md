# luany

> Official application skeleton for the [Luany Framework](https://github.com/luany-ecosystem/luany-framework).

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![Framework](https://img.shields.io/badge/luany%2Fframework-v0.2-5B3171?style=flat-square)](https://packagist.org/packages/luany/framework)
[![License](https://img.shields.io/badge/license-MIT-E6874A?style=flat-square)](LICENSE)

---

## What is this?

This is the starting point for any Luany application. Clone it, run `php luany serve`, and you have a working MVC app with a full request lifecycle, AST template engine, CSRF protection, and a design system ready to build on.

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
│   │   └── HomeController.php          # Example controller
│   ├── Exceptions/
│   │   └── Handler.php                 # Custom exception handler
│   ├── Http/
│   │   └── Kernel.php                  # HTTP kernel — global middleware stack
│   ├── Middleware/
│   │   └── CsrfMiddleware.php          # CSRF token verification
│   ├── Models/
│   │   └── .gitkeep
│   ├── Providers/
│   │   ├── AppServiceProvider.php      # App bindings and singletons
│   │   └── DatabaseServiceProvider.php
│   └── Support/
│       └── helpers.php                 # Global helpers: url(), asset(), env()
│
├── bootstrap/
│   └── app.php                         # Application bootstrap
│
├── database/
│   └── migrations/
│       └── 2026_01_01_000000_create_users_table.php
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
│       │   ├── base.css                # Design system tokens
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
│   │   └── navbar.lte
│   ├── layouts/
│   │   └── main.lte                    # Base HTML layout
│   └── pages/
│       ├── home.lte
│       └── errors/
│           ├── 404.lte                 # Self-contained — no external CSS deps
│           └── 500.lte                 # Self-contained — no external CSS deps
│
├── .env.example
├── .gitignore
├── composer.json
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

@forelse($items as $item)
    <li>{{ $item }}</li>
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
<html>
<head>
    @styles
</head>
<body>
    @include('components.navbar')
    @yield('content')
    @include('components.footer')
    @scripts
</body>
</html>
```

```lte
{{-- views/pages/example.lte --}}
@extends('layouts.main')

@section('content')
    <h1>{{ $title }}</h1>
@endsection

@style
    h1 { color: var(--luany-orange); }
@endstyle
```

### Collocated styles and scripts

`@style` / `@endstyle` and `@script(defer)` / `@endscript` blocks are extracted, deduplicated by hash, and injected at `@styles` / `@scripts` in the layout. Each block renders exactly once no matter how many times a component is included.

---

## Routing

```php
// routes/http.php
use Luany\Core\Routing\Route;

Route::get('/',           [HomeController::class, 'index']);
Route::get('/posts',      [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/posts',     [PostController::class, 'store']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// Named routes — use url('route.name') in views
Route::get('/about', [PageController::class, 'about'])->name('about');

// Groups with shared middleware
Route::group(['middleware' => [CsrfMiddleware::class]], function () {
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

class AuthMiddleware
{
    public function handle(Request $request, \Closure $next): Response
    {
        if (!session('user_id')) {
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

use Luany\Framework\Exceptions\Handler as BaseHandler;
use Luany\Core\Http\Request;
use Luany\Core\Http\Response;

class Handler extends BaseHandler
{
    public function render(Request $request, \Throwable $e): Response
    {
        if ($e instanceof ModelNotFoundException) {
            return Response::make(view('pages.errors.404'), 404);
        }

        return parent::render($request, $e);
    }
}
```

The `404.lte` and `500.lte` error pages are **self-contained** — they have inline CSS and zero dependency on `app.css` or any external assets. They render correctly even if the asset pipeline is broken or an exception occurs during boot.

With `APP_DEBUG=true`, a full-screen debug page shows the exception class, file, line, request info, and stack trace. In production (`APP_DEBUG=false`), the `500.lte` view is served instead.

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
APP_ENV=local
APP_DEBUG=true
APP_KEY=
APP_URL=http://localhost:8000
APP_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=luany
DB_USER=root
DB_PASS=
```

---

## Design system

The skeleton ships with a dark design system built on CSS custom properties. All tokens are in `public/assets/css/base.css`.

Core palette:

| Token | Value | Role |
|-------|-------|------|
| `--luany-purple` | `#5B3171` | Brand primary |
| `--luany-orange` | `#E6874A` | Brand accent |
| `--luany-dark` | `#010213` | Background |
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