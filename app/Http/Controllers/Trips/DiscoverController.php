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
        $filters = $request->toDto();

        $trips = Trip::query()
            ->public()
            ->with(['user', 'likes'])
            ->withCount(['destinations', 'likes'])
            ->when($filters->status, fn ($q, $status) => $q->where('status', $status))
            ->when(
                $filters->country,
                fn ($q, $country) => $q->whereHas('destinations', fn ($q) => $q->where('country_code', $country)),
            )
            ->when($filters->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('destinations', fn ($query) => $query->where('city', 'like', "%{$search}%")
                            ->orWhere('country_code', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(12);

        return TripResource::collection($trips);
    }
}
