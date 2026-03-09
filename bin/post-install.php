<?php

/**
 * post-install.php
 *
 * Runs automatically after `composer create-project luany/luany my-app`.
 * Responsible for:
 *   - Copying .env.example → .env
 *   - Generating a secure APP_KEY
 *   - Displaying the welcome screen
 */

// ── .env ──────────────────────────────────────────────────────────────────────

if (!file_exists('.env')) {
    if (!file_exists('.env.example')) {
        fwrite(STDERR, "\n  \033[31m✗\033[0m  .env.example not found. Cannot create .env.\n\n");
        exit(1);
    }
    copy('.env.example', '.env');
}

// ── APP_KEY ───────────────────────────────────────────────────────────────────

$key     = 'base64:' . base64_encode(random_bytes(32));
$content = file_get_contents('.env');
$content = preg_replace('/^APP_KEY=.*/m', 'APP_KEY=' . $key, $content);
file_put_contents('.env', $content);

// ── Welcome ───────────────────────────────────────────────────────────────────

echo "\n";
echo "  \033[38;5;55m██╗     ██╗   ██╗ █████╗ ███╗   ██╗██╗   ██╗\033[0m\n";
echo "  \033[38;5;55m██║     ██║   ██║██╔══██╗████╗  ██║╚██╗ ██╔╝\033[0m\n";
echo "  \033[38;5;55m██║     ██║   ██║███████║██╔██╗ ██║ ╚████╔╝ \033[0m\n";
echo "  \033[38;5;55m██║     ██║   ██║██╔══██║██║╚██╗██║  ╚██╔╝  \033[0m\n";
echo "  \033[38;5;55m███████╗╚██████╔╝██║  ██║██║ ╚████║   ██║   \033[0m\n";
echo "  \033[38;5;55m╚══════╝ ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝  \033[0m\n";
echo "\n";
echo "  \033[32m✓\033[0m  .env created\n";
echo "  \033[32m✓\033[0m  Application key set\n";
echo "\n";
echo "  \033[38;5;208mNext steps:\033[0m\n";
echo "    1. Configure your database in \033[36m.env\033[0m\n";
echo "    2. \033[36mluany migrate\033[0m\n";
echo "    3. \033[36mluany serve\033[0m\n";
echo "\n";