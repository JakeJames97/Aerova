<?php

namespace Database\Factories;

use App\Enums\TripStatus;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trip>
 */
class TripFactory extends Factory
{
    public function definition(): array
    {
        $startDate = Carbon::instance(fake()->dateTimeBetween('-6 months', '+6 months'));
        $endDate = $startDate->copy()->addDays(fake()->numberBetween(1, 14));

        return [
            'user_id' => User::factory()->create(),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => TripStatus::PLANNED,
        ];
    }

    public function inProgress(): static
    {
        return $this->state(['status' => TripStatus::PROGRESS]);
    }

    public function completed(): static
    {
        return $this->state(['status' => TripStatus::COMPLETED]);
    }
}
