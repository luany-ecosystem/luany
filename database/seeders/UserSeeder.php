<?php

use Luany\Database\Seeder\Seeder;

class UserSeeder extends Seeder
{
    public function run(\PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO `users` (`name`, `email`) VALUES (?, ?)
        ");

        $stmt->execute(['António Ngola', 'antoniongola.dev@gmail.com']);
        $stmt->execute(['Luany António',  'luanyantonio.mykid@gmail.com']);
    }
}