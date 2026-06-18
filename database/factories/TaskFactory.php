<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'destination_id' => Destination::factory()->create(),
            'title' => fake()->sentence(4),
            'is_completed' => false,
        ];
    }

    public function completed(): static
    {
        return $this->state(['is_completed' => true]);
    }
}
