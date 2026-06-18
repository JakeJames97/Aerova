<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    public function definition(): array
    {
        $arrivalDate = Carbon::instance(fake()->dateTimeBetween('-3 months', '+3 months'));
        $departureDate = $arrivalDate->copy()->addDays(fake()->numberBetween(1, 7));

        return [
            'trip_id' => Trip::factory()->create(),
            'name' => fake()->city(),
            'arrival_date' => $arrivalDate,
            'departure_date' => $departureDate,
        ];
    }
}
