<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $trips = $request->user()
            ->trips()
            ->withCount('destinations')
            ->latest('start_date')
            ->get();

        return TripResource::collection($trips);
    }
}
