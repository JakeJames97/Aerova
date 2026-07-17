<?php

namespace App\Http\Controllers\Transports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transports\UpdateTransportRequest;
use App\Http\Resources\TransportResource;
use App\Models\Transport;

class UpdateController extends Controller
{
    public function __invoke(UpdateTransportRequest $request, Transport $transport): TransportResource
    {
        $transport->update($request->toDto()->toArray());

        return new TransportResource($transport);
    }
}
