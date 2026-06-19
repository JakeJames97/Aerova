<?php

use App\Http\Controllers\Tasks\CreateController;
use App\Http\Controllers\Tasks\DeleteController;
use App\Http\Controllers\Tasks\ToggleController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/destinations/{destination}/tasks', CreateController::class);
    Route::patch('/tasks/{task}/toggle', ToggleController::class)->can('update', 'task');
    Route::delete('/tasks/{task}', DeleteController::class)->can('delete', 'task');
});
