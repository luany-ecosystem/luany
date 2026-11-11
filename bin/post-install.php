<?php

/**
 * Runs after `composer create-project luany/luany`:
 * creates .env with a fresh APP_KEY and the SQLite database file.
 */

if (!file_exists('.env')) {
    if (!file_exists('.env.example')) {
        fwrite(STDERR, "\n  \033[31m✗\033[0m  .env.example not found. Cannot create .env.\n\n");
        exit(1);
    }
    copy('.env.example', '.env');
}

$env = (string) file_get_contents('.env');
$env = (string) preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=base64:' . base64_encode(random_bytes(32)), $env);
file_put_contents('.env', $env);

$usesSqlite = preg_match('/^DB_CONNECTION=sqlite\s*$/m', $env) === 1;
if ($usesSqlite && preg_match('/^DB_NAME=(.+)$/m', $env, $m) === 1) {
    $file = trim($m[1], " \t\"'");
    if ($file !== ':memory:' && !file_exists($file)) {
        @mkdir(dirname($file), 0755, true);
        touch($file);
    }
}

echo "\n  \033[32m✓\033[0m  .env created with a new APP_KEY\n";
if ($usesSqlite) {
    echo "  \033[32m✓\033[0m  SQLite database ready (change DB_CONNECTION in .env for MySQL / PostgreSQL)\n";
}
echo "\n  Next:\n";
echo "    luany migrate     create the tables\n";
echo "    luany dev         start the server with live reload  →  http://localhost:8000\n\n";
