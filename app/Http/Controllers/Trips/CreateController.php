<?php

namespace App\Http\Controllers\Trips;

use App\Exceptions\TripException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trips\CreateTripRequest;
use App\Http\Resources\TripResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreateController extends Controller
{
    public function __invoke(CreateTripRequest $request): JsonResponse
    {
        $data = collect($request->validated());

        try {
            $trip = DB::transaction(static function () use ($request, $data) {
                $trip = $request->user()->trips()->create($data->except('destinations')->toArray());
                $destinations = $data->get('destinations') ?? [];

                foreach ($destinations as $destination) {
                    $trip->destinations()->create($destination);
                }

                return $trip;
            });
        } catch (Throwable $throwable) {
            Log::error('An exception was thrown when attempting to create a trip', [
                'exception' => $throwable,
            ]);

            throw new TripException('Unexpected error when creating trip');
        }

        $trip->load('destinations.country');

        return new TripResource($trip)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
