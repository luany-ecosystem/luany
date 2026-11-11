<?php

use Luany\Database\Migration\Migration;
use Luany\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(\PDO $pdo): void
    {
        $this->schema($pdo)->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down(\PDO $pdo): void
    {
        $this->schema($pdo)->dropIfExists('users');
    }
};
