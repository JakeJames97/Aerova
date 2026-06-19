<?php

namespace App\Http\Controllers\Destinations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Destinations\CreateDestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;
use App\Models\Trip;
use Gate;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends Controller
{
    public function __invoke(CreateDestinationRequest $request, Trip $trip): JsonResponse
    {
        Gate::authorize('create', [Destination::class, $trip]);

        $destination = $trip->destinations()->create($request->validated());

        return new DestinationResource($destination)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
