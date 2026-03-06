<?php

namespace LuanyCli\Commands;

use Luany\Database\Migration\MigrationRunner;

/**
 * MigrateCommand
 *
 * Runs all pending migrations.
 * All migration logic lives in MigrationRunner (luany/database).
 * This command only handles CLI I/O.
 *
 * Usage: php luany migrate
 */
class MigrateCommand
{
    public function handle(array $args): void
    {
        echo "\n";

        $count = $this->runner()->run(function (string $name, string $status) {
            if ($status === 'nothing') {
                echo "  \033[33m→\033[0m  Nothing to migrate.\n";
                return;
            }
            echo "  \033[32m✓\033[0m  Migrated: {$name}\n";
        });

        if ($count > 0) {
            echo "\n  \033[32m✓\033[0m  {$count} migration(s) complete.\n";
        }

        echo "\n";
    }

    private function runner(): MigrationRunner
    {
        return new MigrationRunner(
            $this->pdo(),
            BASE_DIR . '/database/migrations'
        );
    }

    private function pdo(): \PDO
    {
        $env = $this->loadEnv();
        $dsn = "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_NAME']};charset=utf8mb4";

        return new \PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    private function loadEnv(): array
    {
        $file = BASE_DIR . '/.env';

        if (!file_exists($file)) {
            fwrite(STDERR, "\n  \033[31m✗\033[0m  .env not found. Run: php luany key:generate\n\n");
            exit(1);
        }

        return parse_ini_file($file) ?: [];
    }
}