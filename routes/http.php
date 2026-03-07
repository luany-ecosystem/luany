<?php

use Luany\Core\Routing\Route;
use App\Controllers\HomeController;
use App\Controllers\Locale\LocaleController;

/*
|--------------------------------------------------------------------------
| HTTP Routes
|--------------------------------------------------------------------------
|
| Routes are matched top-to-bottom. The first match wins.
| Route parameters are available in controller methods as typed arguments.
|
*/

// ── Home ─────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index']);

// ── Locale ───────────────────────────────────────────────────────────────
// GET /locale/{lang}  —  sets cookie and redirects back
// No CSRF needed: GET request, no state mutation beyond a cookie preference.
Route::get('/locale/{lang}', [LocaleController::class, 'switch']);