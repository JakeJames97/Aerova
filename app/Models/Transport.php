<?php

namespace App\Models;

use App\Enums\TransportType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transport extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'type',
        'from',
        'to',
        'identifier',
        'departure_at',
        'arrival_at',
        'price',
        'airline',
        'from_iata',
        'to_iata',
        'current_price',
        'price_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => TransportType::class,
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
            'price_checked_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Destination, $this>
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
