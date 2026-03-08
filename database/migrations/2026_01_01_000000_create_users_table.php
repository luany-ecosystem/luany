<?php

use Luany\Database\Migration\Migration;

/**
 * CreateUsersTable
 *
 * Example migration — run with: luany migrate
 */
class CreateUsersTable extends Migration
{
    public function up(\PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users` (
                `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name`       VARCHAR(100) NOT NULL,
                `email`      VARCHAR(150) NOT NULL UNIQUE,
                `password`   VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(\PDO $pdo): void
    {
        $pdo->exec("DROP TABLE IF EXISTS `users`");
    }
}