<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    public function definition(): array
    {
        $countries = [
            ['code' => 'GB', 'name' => 'United Kingdom'],
            ['code' => 'FR', 'name' => 'France'],
            ['code' => 'JP', 'name' => 'Japan'],
            ['code' => 'US', 'name' => 'United States'],
            ['code' => 'IT', 'name' => 'Italy'],
        ];

        return $this->faker->randomElement($countries);
    }
}
