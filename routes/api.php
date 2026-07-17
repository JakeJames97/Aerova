<?php

use App\Http\Controllers\CountriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Profile\DeleteController;
use App\Http\Controllers\SearchAirportsController;

require __DIR__ . '/auth.php';
require __DIR__ . '/trips.php';
require __DIR__ . '/destinations.php';
require __DIR__ . '/tasks.php';
require __DIR__ . '/notifications.php';
require __DIR__ . '/transports.php';

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
Route::get('/countries', CountriesController::class);
Route::get('/airports', SearchAirportsController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', DashboardController::class);
    Route::delete('/profile', DeleteController::class);
});
