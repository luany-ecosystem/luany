<?php

namespace LuanyCli\Commands;

class MakeProviderCommand
{
    public function handle(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            fwrite(STDERR, "\n  \033[31m✗\033[0m  Usage: php luany make:provider <ProviderName>\n\n");
            exit(1);
        }

        $name = $this->normalise($name, 'ServiceProvider');
        $path = BASE_DIR . "/app/Providers/{$name}.php";

        if (file_exists($path)) {
            echo "\n  \033[33m⚠\033[0m  {$name} already exists.\n\n";
            exit(0);
        }

        file_put_contents($path, $this->stub($name));

        echo "\n  \033[32m✓\033[0m  Provider created: app/Providers/{$name}.php\n\n";
    }

    private function stub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Providers;

use Luany\Framework\Application;
use Luany\Framework\ServiceProvider;

class {$name} extends ServiceProvider
{
    public function register(Application \$app): void
    {
        //
    }

    public function boot(Application \$app): void
    {
        //
    }
}
PHP;
    }

    private function normalise(string $name, string $suffix): string
    {
        $name = ucfirst($name);
        return str_ends_with($name, $suffix) ? $name : $name . $suffix;
    }
}