<?php

namespace LuanyCli\Commands;

class MakeControllerCommand
{
    public function handle(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            fwrite(STDERR, "\n  \033[31m✗\033[0m  Usage: php luany make:controller <ControllerName>\n\n");
            exit(1);
        }

        $name = $this->normalise($name, 'Controller');
        $path = BASE_DIR . "/app/Controllers/{$name}.php";

        if (file_exists($path)) {
            echo "\n  \033[33m⚠\033[0m  {$name} already exists.\n\n";
            exit(0);
        }

        file_put_contents($path, $this->stub($name));

        echo "\n  \033[32m✓\033[0m  Controller created: app/Controllers/{$name}.php\n\n";
    }

    private function stub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Controllers;

use Luany\Core\Http\Request;

class {$name} extends Controller
{
    public function index(Request \$request): string
    {
        return view('pages.home');
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