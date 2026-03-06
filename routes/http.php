<?php

use Luany\Core\Routing\Route;
use App\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
| Register all application routes here.
| Loaded automatically by the Kernel on boot.
|
| Available methods:
|   Route::get(), post(), put(), patch(), delete(), any()
|   Route::resource(), apiResource()
|   Route::view('/path', 'view.name', $data)
|   Route::prefix('prefix')->middleware(Auth::class)->group(fn() => ...)
|   ->name('route.name')
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');