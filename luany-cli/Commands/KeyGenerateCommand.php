<?php

namespace LuanyCli\Commands;

class KeyGenerateCommand
{
    public function handle(array $args): void
    {
        $envFile = BASE_DIR . '/.env';

        if (!file_exists($envFile)) {
            $example = BASE_DIR . '/.env.example';
            if (!file_exists($example)) {
                fwrite(STDERR, "\n  \033[31m✗\033[0m  .env.example not found.\n\n");
                exit(1);
            }
            copy($example, $envFile);
            echo "\n  \033[32m✓\033[0m  .env created from .env.example\n";
        }

        $key     = 'base64:' . base64_encode(random_bytes(32));
        $content = file_get_contents($envFile);

        if (str_contains($content, 'APP_KEY=')) {
            $content = preg_replace('/^APP_KEY=.*/m', "APP_KEY={$key}", $content);
        } else {
            $content .= "\nAPP_KEY={$key}";
        }

        file_put_contents($envFile, $content);
        echo "  \033[32m✓\033[0m  Application key set.\n\n";
    }
}