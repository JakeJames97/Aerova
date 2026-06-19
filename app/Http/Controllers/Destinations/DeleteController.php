<?php

namespace App\Http\Controllers\Destinations;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Response;

class DeleteController extends Controller
{
    public function __invoke(Destination $destination): Response
    {
        $destination->delete();

        return response()->noContent();
    }
}
