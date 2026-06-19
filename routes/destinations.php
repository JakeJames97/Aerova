<?php

use App\Http\Controllers\Destinations\CreateController;
use App\Http\Controllers\Destinations\DeleteController;
use App\Http\Controllers\Destinations\UpdateController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/trips/{trip}/destinations', CreateController::class);
    Route::put('/destinations/{destination}', UpdateController::class)->can('update', 'destination');
    Route::delete('/destinations/{destination}', DeleteController::class)->can('delete', 'destination');
});
