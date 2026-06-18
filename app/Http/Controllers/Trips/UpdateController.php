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
        $trip->update($request->validated());

        $trip->loadCount('destinations');

        return new TripResource($trip);
    }
}
