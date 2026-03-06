<?php

/**
 * Luany — Front Controller
 *
 * Single entry point for all HTTP requests.
 * Lifecycle: Bootstrap → Boot → Handle → Send → Terminate
 */

use Luany\Framework\Http\Kernel;
use Luany\Core\Http\Request;

$app = require dirname(__DIR__) . '/bootstrap/app.php';

$kernel   = $app->make(Kernel::class);
$kernel->boot();

$request  = Request::fromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);