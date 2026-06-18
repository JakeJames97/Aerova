<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trips\CreateTripRequest;
use App\Http\Resources\TripResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends Controller
{
    public function __invoke(CreateTripRequest $request): JsonResponse
    {
        $trip = $request->user()->trips()->create($request->validated());

        $trip->loadCount('destinations');

        return new TripResource($trip)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
