<?php

use App\Http\Controllers\Notifications\IndexController;
use App\Http\Controllers\Notifications\MarkAsReadController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', IndexController::class);
    Route::patch('/notifications/{notification}/read', MarkAsReadController::class);
});
