<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trips\GetTripsRequest;
use App\Http\Resources\TripResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(GetTripsRequest $request): AnonymousResourceCollection
    {
        $params = $request->toDto();

        $trips = $request->user()
            ->trips()
            ->withCount('destinations')
            ->withCount('likes')
            ->with('likes')
            ->when($params->status, fn ($q, $status) => $q->where('status', $status))
            ->latest('start_date')
            ->paginate();

        return TripResource::collection($trips);
    }
}
