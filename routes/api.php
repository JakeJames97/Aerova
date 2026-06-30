<?php

use App\Http\Controllers\CountriesController;
use App\Http\Controllers\DashboardController;

require __DIR__ . '/auth.php';
require __DIR__ . '/trips.php';
require __DIR__ . '/destinations.php';
require __DIR__ . '/tasks.php';
require __DIR__ . '/notifications.php';

Route::get('/countries', CountriesController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', DashboardController::class);
});
