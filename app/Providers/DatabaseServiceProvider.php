<?php

namespace App\Providers;

use Luany\Database\Connection;
use Luany\Database\Model;
use Luany\Framework\Application;
use Luany\Framework\ServiceProvider;
use Luany\Framework\Support\Env;

/**
 * DatabaseServiceProvider
 *
 * Registers the database connection as a lazy singleton.
 * The PDO connection is only opened when first resolved.
 *
 * Also wires the Connection into Model::setConnection()
 * so all models work without knowing about the container.
 */
class DatabaseServiceProvider extends ServiceProvider
{
    public function register(Application $app): void
    {
        // Lazy singleton — PDO not opened until first use
        $app->singleton('connection', function () {
            return Connection::make([
                'host'     => Env::get('DB_HOST', '127.0.0.1'),
                'port'     => Env::get('DB_PORT', '3306'),
                'database' => Env::get('DB_NAME', 'luany'),
                'username' => Env::get('DB_USER', 'root'),
                'password' => Env::get('DB_PASS', ''),
                'charset'  => 'utf8mb4',
            ]);
        });

        // app('db') → raw PDO for QueryBuilder, CLI migrate commands, etc.
        $app->singleton('db', fn($app) => $app->make('connection')->getPdo());
    }

    public function boot(Application $app): void
    {
        // Wire shared Connection into Model base class
        // Still lazy — opening PDO only happens on first query
        Model::setConnection(fn() => $app->make('connection'));
    }
}