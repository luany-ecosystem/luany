<?php

use App\Controllers\HomeController;
use Luany\Core\Routing\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
