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
class MigrateCommand extends MigrateBaseCommand
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
        return new MigrationRunner($this->pdo(), $this->migrationPath());
    }
}
