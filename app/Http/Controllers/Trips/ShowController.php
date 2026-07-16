<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Request $request, Trip $trip): TripResource
    {
        $trip->load([
            'destinations',
            'destinations.country',
            'destinations.tasks',
            'destinations.transports',
        ])->loadCount('likes');

        return new TripResource($trip);
    }
}
