<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class ToggleController extends Controller
{
    public function __invoke(Request $request, Task $task): TaskResource
    {
        $task->update(['is_completed' => !$task->is_completed]);

        return new TaskResource($task);
    }
}
