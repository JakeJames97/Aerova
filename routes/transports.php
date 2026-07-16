<?php

use App\Http\Controllers\Transports\CreateController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/destinations/{destination}/transports', CreateController::class);
});
