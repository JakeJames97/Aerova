<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchAirportsRequest;
use App\Http\Resources\AirportResource;
use App\Models\Airport;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchAirportsController extends Controller
{
    public function __invoke(SearchAirportsRequest $request): AnonymousResourceCollection
    {
        $search = $request->validated('search');

        $airports = Airport::query()
            ->select(['iata', 'name'])
            ->where('iata', 'like', "{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return AirportResource::collection($airports);
    }
}
