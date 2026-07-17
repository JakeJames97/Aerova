<?php

use App\Http\Controllers\Transports\CreateController;
use App\Http\Controllers\Transports\DeleteController;
use App\Http\Controllers\Transports\UpdateController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/destinations/{destination}/transports', CreateController::class);
    Route::delete('/transports/{transport}', DeleteController::class)->can('delete', 'transport');
    Route::put('/transports/{transport}', UpdateController::class)->can('update', 'transport');
});
