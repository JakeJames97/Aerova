<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

/**
 * @property string $iata
 * @property string $name
 */
class Airport extends Model
{
    use HasFactory, Sushi;

    public function getRows(): array
    {
        $airports = json_decode(file_get_contents(resource_path('data/airports.json')), true);

        return collect($airports)
            ->filter(fn (array $airport) => !empty($airport['iata']) && !empty($airport['name']) && ($airport['type'] ?? null) === 'airport')
            ->map(fn (array $airport) => [
                'iata' => $airport['iata'],
                'name' => $airport['name'] ?? null,
            ])
            ->values()
            ->all();
    }
}
