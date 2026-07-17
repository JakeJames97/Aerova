<?php

namespace App\Http\Controllers\Transports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transports\LookupFlightsRequest;
use App\Http\Resources\FlightResource;
use App\Models\Destination;
use App\Models\Transport;
use App\Services\FlightService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class LookupController extends Controller
{
    public function __invoke(
        LookupFlightsRequest $request,
        Destination $destination,
        FlightService $flights,
    ): AnonymousResourceCollection {
        Gate::authorize('create', [Transport::class, $destination]);

        $data = $request->toDto();
        $results = $flights->lookup(
            $data->fromIata,
            $data->toIata,
            $data->date,
        );

        return FlightResource::collection($results);
    }
}
