<?php

namespace App\Http\Controllers\Destinations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Destinations\UpdateDestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;

class UpdateController extends Controller
{
    public function __invoke(UpdateDestinationRequest $request, Destination $destination): DestinationResource
    {
        $destination->update($request->toDto()->toArray());

        return new DestinationResource($destination);
    }
}
