<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Destination;
use App\Models\Task;
use Gate;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends Controller
{
    public function __invoke(CreateTaskRequest $request, Destination $destination): JsonResponse
    {
        Gate::authorize('create', [Task::class, $destination]);

        $task = $destination->tasks()->create([
            ...$request->validated(),
            'is_completed' => false,
        ]);

        return new TaskResource($task)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
