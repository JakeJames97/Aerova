<?php

namespace App\Http\Requests\Tasks;

use App\Data\Tasks\CreateTaskData;
use App\Http\Requests\BaseFormRequest;

class CreateTaskRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDto(): CreateTaskData
    {
        return CreateTaskData::fromRequest($this);
    }
}
