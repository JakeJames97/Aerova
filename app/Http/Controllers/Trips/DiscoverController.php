<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trips\GetTripsRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DiscoverController extends Controller
{
    public function __invoke(GetTripsRequest $request): AnonymousResourceCollection
    {
        $params = $request->validated();
        $page = array_key_exists('page', $params) ? $params['page'] : 1;

        $trips = Trip::query()
            ->public()
            ->with(['user', 'likes'])
            ->withCount(['destinations', 'likes'])
            ->when($params['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when(
                $params['country'] ?? null, fn ($q, $country) => $q->whereHas('destinations', fn ($q) => $q->where('country_code', $country))
            )
            ->when($params['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('destinations', fn ($query) => $query->where('city', 'like', "%{$search}%")
                            ->orWhere('country_code', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(page: $page);

        return TripResource::collection($trips);
    }
}
