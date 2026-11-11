<?php

namespace App\Providers;

use Luany\Database\Connection;
use Luany\Database\Model;
use Luany\Framework\Application;
use Luany\Framework\ServiceProvider;
use Luany\Framework\Support\Env;

/**
 * Database connection from .env — opened lazily, on the first query.
 *
 *   DB_CONNECTION=sqlite   DB_NAME=database/database.sqlite
 *   DB_CONNECTION=mysql    DB_HOST / DB_PORT / DB_NAME / DB_USER / DB_PASS
 *   DB_CONNECTION=pgsql    DB_HOST / DB_PORT / DB_NAME / DB_USER / DB_PASS
 */
class DatabaseServiceProvider extends ServiceProvider
{
    public function register(Application $app): void
    {
        $app->singleton('connection', function () use ($app) {
            $driver = (string) Env::get('DB_CONNECTION', 'sqlite');
            $name   = (string) Env::get('DB_NAME', $driver === 'sqlite' ? 'database/database.sqlite' : 'luany');

            if ($driver === 'sqlite' && $name !== ':memory:' && !str_starts_with($name, '/') && !preg_match('/^[A-Za-z]:[\\\\\/]/', $name)) {
                $name = $app->basePath($name);
            }

            return Connection::make([
                'driver'   => $driver,
                'host'     => Env::get('DB_HOST', '127.0.0.1'),
                'port'     => Env::get('DB_PORT', $driver === 'pgsql' ? '5432' : '3306'),
                'database' => $name,
                'username' => Env::get('DB_USER', $driver === 'mysql' ? 'root' : null),
                'password' => Env::get('DB_PASS', ''),
            ]);
        });

        // Raw PDO: used by validation rules (unique / exists) and migrations.
        $app->singleton('db', fn (Application $app) => $app->make('connection')->getPdo());
    }

    public function boot(Application $app): void
    {
        Model::setConnection(fn () => $app->make('connection'));
    }
}
