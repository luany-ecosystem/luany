<?php

namespace App\Controllers;

/**
 * Controller
 *
 * Base class for all application controllers.
 * Currently empty — reserved for shared controller behaviour.
 *
 * Extend to add helpers used across all controllers:
 *   - authorize()
 *   - validate()
 *   - dispatch()
 *
 * IMPORTANT: do NOT add middleware here.
 * Middleware belongs on the route or in the Kernel:
 *
 *   Route::get('/dashboard', [DashboardController::class, 'index'])
 *       ->middleware(AuthMiddleware::class);
 */
abstract class Controller
{
    //
}