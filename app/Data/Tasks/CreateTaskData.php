<?php

namespace App\Data\Tasks;

use App\Http\Requests\Tasks\CreateTaskRequest;

final readonly class CreateTaskData
{
    public function __construct(public string $title) {}

    public static function fromRequest(CreateTaskRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
        );
    }
}
