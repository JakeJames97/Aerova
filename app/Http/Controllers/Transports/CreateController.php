<?php

namespace App\Http\Controllers\Transports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transports\CreateTransportRequest;
use App\Http\Resources\TransportResource;
use App\Models\Destination;
use App\Models\Transport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends Controller
{
    public function __invoke(CreateTransportRequest $request, Destination $destination): JsonResponse
    {
        Gate::authorize('create', [Transport::class, $destination]);

        $transport = $destination->transports()->create(
            $request->toDto()->toArray()
        );

        return new TransportResource($transport)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
