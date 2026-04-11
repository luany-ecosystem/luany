<?php

use Luany\Database\Seeder\Seeder;

/**
 * DatabaseSeeder
 *
 * Entry point for all seeders — run with: luany db:seed
 * Call individual seeders using $this->call().
 */
class DatabaseSeeder extends Seeder
{
    public function run(\PDO $pdo): void
    {
        $this->call(UserSeeder::class);
    }
}