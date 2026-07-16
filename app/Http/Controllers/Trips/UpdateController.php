<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trips\UpdateTripRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;

class UpdateController extends Controller
{
    public function __invoke(UpdateTripRequest $request, Trip $trip): TripResource
    {
        $data = $request->toDto();
        $trip->update($data->toArray());

        $trip->load([
            'destinations',
            'destinations.country',
            'destinations.tasks',
        ]);

        return new TripResource($trip);
    }
}
