<?php

namespace Database\Factories;

use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Airport>
 */
class AirportFactory extends Factory
{
    public function definition(): array
    {
        $airports = [
            ['iata' => 'LHR', 'name' => 'London Heathrow Airport'],
            ['iata' => 'BHX', 'name' => 'Birmingham Airport'],
            ['iata' => 'HIJ', 'name' => 'Hiroshima Airport'],
        ];

        return $this->faker->randomElement($airports);
    }
}
