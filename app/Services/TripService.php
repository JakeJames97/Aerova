<?php

namespace App\Services;

use App\Enums\TripStatus;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class TripService
{
    /**
     * @throws Throwable
     */
    public function clone(Trip $trip, User $user): Trip
    {
        $trip->load(['destinations.tasks', 'destinations.transports']);

        return DB::transaction(static function () use ($trip, $user) {
            $newTrip = $user->trips()->create([
                'name' => "{$trip->name} (copy)",
                'description' => $trip->description,
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date,
                'status' => TripStatus::PLANNED,
                'is_public' => false,
            ]);

            foreach ($trip->destinations as $destination) {
                $newDestination = $newTrip->destinations()->create([
                    'city' => $destination->city,
                    'country_code' => $destination->country_code,
                    'budget' => $destination->budget,
                    'arrival_date' => $destination->arrival_date,
                    'departure_date' => $destination->departure_date,
                ]);

                foreach ($destination->transports as $transport) {
                    $newDestination->transports()->create([
                        'from' => $transport->from,
                        'to' => $transport->to,
                        'type' => $transport->type,
                        'identifier' => $transport->identifier,
                        'price' => $transport->price,
                        'airline' => $transport->airline,
                        'arrival_at' => $transport->arrival_at,
                        'departure_at' => $transport->departure_at,
                        'from_iata' => $transport->from_iata,
                        'to_iata' => $transport->to_iata,
                    ]);
                }

                foreach ($destination->tasks as $task) {
                    $newDestination->tasks()->create([
                        'title' => $task->title,
                        'is_completed' => false,
                    ]);
                }
            }

            return $newTrip;
        });
    }
}
