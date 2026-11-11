# Luany

The application skeleton for the [Luany Framework](https://github.com/luany-ecosystem) — a clean
starting point with nothing you have to delete first.

## Requirements

- PHP 8.2+ with `pdo_sqlite` (or `pdo_mysql` / `pdo_pgsql`)
- Composer
- Node.js — only for live reload (`luany dev`)

## Create a project

```bash
composer global require luany/cli
luany new my-app            # or: composer create-project luany/luany my-app
cd my-app
luany migrate
luany dev                   # http://localhost:8000 with live reload
```

A new project uses SQLite out of the box. For MySQL or PostgreSQL, set `DB_CONNECTION`
and the `DB_*` variables in `.env`.

## Structure

```
app/
  Controllers/          HTTP controllers
  Exceptions/Handler    error pages and reporting
  Http/Kernel           global middleware
  Models/               database models
  Providers/            service providers (app, database)
bootstrap/app.php       application bootstrap
config/app.php          name, environment, URL, timezone, locale
database/
  migrations/           schema migrations
  seeders/              database seeders
public/                 web root (index.php, assets)
routes/http.php         routes
storage/                cache, logs, sessions
tests/                  PHPUnit tests
views/                  LTE templates (layouts, pages, components, errors)
```

## Next steps

```bash
luany make:feature Post title:string body:text published:boolean   # model, migration, controller, views, routes
luany view:check                                                    # validate all views
composer test                                                       # run the test suite
```

Documentation: [docs.luany.dev](https://docs.luany.dev)

## License

MIT
