<?php

namespace Database\Factories;

use App\Enums\TransportType;
use App\Models\Destination;
use App\Models\Transport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Transport>
 */
class TransportFactory extends Factory
{
    protected $model = Transport::class;

    public function definition(): array
    {
        $departure = $this->faker->dateTimeBetween('+1 month', '+6 months');
        $arrival = Carbon::instance(fake()->dateTimeBetween('-3 months', '+3 months'));

        return [
            'destination_id' => Destination::factory(),
            'type' => TransportType::FLIGHT,
            'from' => $this->faker->city() . ' Airport',
            'to' => $this->faker->city() . ' Airport',
            'identifier' => $this->faker->randomElement(['BA', 'LH', 'AF', 'KL']) . $this->faker->numberBetween(100, 9999),
            'departure_at' => $departure,
            'arrival_at' => $arrival,
            'price' => $this->faker->numberBetween(5000, 80000),
            'airline' => $this->faker->randomElement(['British Airways', 'Lufthansa', 'Air France', 'KLM']),
            'from_iata' => strtoupper($this->faker->lexify('???')),
            'to_iata' => strtoupper($this->faker->lexify('???')),
        ];
    }

    public function train(): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransportType::TRAIN,
            'from' => $this->faker->city() . ' Station',
            'to' => $this->faker->city() . ' Station',
            'identifier' => $this->faker->randomElement(['Nozomi', 'Hikari', 'Eurostar']) . ' ' . $this->faker->numberBetween(1, 99),
            'airline' => null,
            'from_iata' => null,
            'to_iata' => null,
        ]);
    }

    public function priceChecked(?int $currentPrice = null): self
    {
        return $this->state(fn (array $attributes) => [
            'current_price' => $currentPrice ?? $this->faker->numberBetween(5000, 80000),
            'price_checked_at' => now(),
        ]);
    }
}
