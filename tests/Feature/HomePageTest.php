<?php

namespace Tests\Feature;

use Luany\Core\Http\Request;
use Luany\Framework\Http\Kernel;
use PHPUnit\Framework\TestCase;

class HomePageTest extends TestCase
{
    private function handle(string $method, string $uri): \Luany\Core\Http\Response
    {
        $app    = require dirname(__DIR__, 2) . '/bootstrap/app.php';
        $kernel = $app->make(Kernel::class);
        $kernel->boot();

        return $kernel->handle(new Request($method, $uri, server: ['REMOTE_ADDR' => '127.0.0.1']));
    }

    public function test_home_page_renders(): void
    {
        $response = $this->handle('GET', '/');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('is ready.', $response->getBody());
        $this->assertStringContainsString('images/luany-mark-64.webp', $response->getBody());
    }

    public function test_unknown_page_renders_the_404_view(): void
    {
        $response = $this->handle('GET', '/does-not-exist');

        $this->assertSame(404, $response->getStatusCode());
        $this->assertStringContainsString('Page not found', $response->getBody());
    }
}
