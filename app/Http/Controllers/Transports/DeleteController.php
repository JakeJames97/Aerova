<?php

namespace App\Http\Controllers\Transports;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteController extends Controller
{
    public function __invoke(Request $request, Transport $transport): Response
    {
        $transport->delete();

        return response()->noContent();
    }
}
