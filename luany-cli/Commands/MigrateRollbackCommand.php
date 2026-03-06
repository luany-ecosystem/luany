<?php

namespace LuanyCli\Commands;

use Luany\Database\Migration\MigrationRunner;

/**
 * MigrateRollbackCommand
 *
 * Rolls back the last batch of migrations.
 * All logic lives in MigrationRunner (luany/database).
 *
 * Usage: php luany migrate:rollback
 */
class MigrateRollbackCommand
{
    public function handle(array $args): void
    {
        echo "\n";

        $count = $this->runner()->rollback(function (string $name, string $status) {
            if ($status === 'nothing') {
                echo "  \033[33m→\033[0m  Nothing to rollback.\n";
                return;
            }
            echo "  \033[32m✓\033[0m  Rolled back: {$name}\n";
        });

        if ($count > 0) {
            echo "\n  \033[32m✓\033[0m  {$count} migration(s) rolled back.\n";
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
        $file = BASE_DIR . '/.env';

        if (!file_exists($file)) {
            fwrite(STDERR, "\n  \033[31m✗\033[0m  .env not found.\n\n");
            exit(1);
        }

        $env = parse_ini_file($file) ?: [];
        $dsn = "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_NAME']};charset=utf8mb4";

        return new \PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }
}