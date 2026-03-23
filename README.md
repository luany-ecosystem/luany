# luany/luany

Official application skeleton for the Luany Framework.

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![Framework](https://img.shields.io/badge/luany%2Fframework-v1.0-5B3171?style=flat-square)](https://packagist.org/packages/luany/framework)
[![License](https://img.shields.io/badge/license-MIT-E6874A?style=flat-square)](LICENSE)

## Requirements

- PHP 8.2+
- Composer 2.0+

## Installation

```bash
composer global require luany/cli
luany new my-app
cd my-app
```

Or directly via Composer:

```bash
composer create-project luany/luany my-app
cd my-app
```

## Getting started

````bash
# 1. Configure your database
#    Edit DB_HOST, DB_NAME, DB_USER, DB_PASS in .env

# 2. Verify your environment
luany doctor

# 3. Run migrations
luany migrate

## Development
```bash
luany serve
````

Open `http://localhost:8000`.

For automatic browser reload during development:

```bash
npm install
npm run dev
```

Open `http://localhost:3000`.

Starts the PHP server and enables live reload via BrowserSync — views and assets reload automatically on change.```

## Directory structure

```
my-app/
├── app/
│   ├── Controllers/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Kernel.php
│   │   └── Middleware/
│   ├── Models/
│   ├── Providers/
│   └── Support/
├── bootstrap/
│   └── app.php
├── config/
│   ├── app.php
│   └── mail.php
├── database/
│   └── migrations/
├── lang/
│   ├── en.php
│   └── pt.php
├── public/
│   ├── index.php
│   └── assets/
├── routes/
│   └── http.php
├── storage/
│   ├── cache/views/
│   └── logs/
├── views/
│   ├── components/
│   ├── layouts/
│   └── pages/
├── .env.example
└── composer.json
```

## Key concepts

**Routing** — defined in `routes/http.php`:

```php
Route::get('/', [HomeController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
```

**Controllers** — extend the base `Controller`:

```php
class HomeController extends Controller
{
    public function index(Request $request): string
    {
        return view('pages.home', compact('data'));
    }
}
```

**Views** — LTE template engine, stored in `views/`:

```lte
@extends('layouts.main')

@section('title')My Page@endsection

@section('content')
    <h1>{{ $title }}</h1>
@endsection
```

**Migrations** — in `database/migrations/`:

```php
class CreateUsersTable extends Migration
{
    public function up(\PDO $pdo): void
    {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (...)");
    }

    public function down(\PDO $pdo): void
    {
        $pdo->exec("DROP TABLE IF EXISTS `users`");
    }
}
```

**Models** — ActiveRecord base:

```php
class User extends Model
{
    protected string $table   = 'users';
    protected array $fillable = ['name', 'email', 'password'];
    protected array $hidden   = ['password'];
}
```

## CLI reference

```bash
luany make:controller <Name>      # scaffold controller
luany make:model <Name>           # scaffold model
luany make:migration <name>       # generate migration file
luany make:middleware <Name>      # scaffold middleware
luany make:view <name> [type]     # create LTE view
luany make:feature <Name>         # scaffold full CRUD feature
luany make:request <Name>         # scaffold form request class
luany make:test <Name>            # scaffold PHPUnit test class
luany route:list                  # list all registered routes
luany migrate                     # run pending migrations
luany migrate:status              # show migration status
luany migrate:rollback            # rollback last batch
luany migrate:fresh               # drop all and re-migrate
luany key:generate                # regenerate APP_KEY
luany cache:clear                 # clear compiled views
luany doctor                      # environment health check
luany serve                       # start dev server
```

## Documentation

[docs.luany.dev](https://docs.luany.dev)

## License

MIT — see [LICENSE](LICENSE).
