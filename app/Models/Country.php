<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Country extends Model
{
    use HasFactory, Sushi;

    protected array $rows = [];

    public function getRows(): array
    {
        return json_decode(
            file_get_contents(resource_path('data/countries.json')),
            true
        )['countries'];
    }
}
