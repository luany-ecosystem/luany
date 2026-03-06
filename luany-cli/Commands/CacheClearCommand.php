<?php

namespace LuanyCli\Commands;

class CacheClearCommand
{
    public function handle(array $args): void
    {
        $path  = BASE_DIR . '/storage/cache/views';
        $files = glob($path . '/*.php') ?: [];

        foreach ($files as $file) {
            unlink($file);
        }

        $count = count($files);
        echo "\n  \033[32m✓\033[0m  Cache cleared ({$count} file(s) removed).\n\n";
    }
}