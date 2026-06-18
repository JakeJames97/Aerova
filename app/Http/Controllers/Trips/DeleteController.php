<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteController extends Controller
{
    public function __invoke(Trip $trip, Request $request): Response
    {
        $trip->delete();

        return response()->noContent();
    }
}
