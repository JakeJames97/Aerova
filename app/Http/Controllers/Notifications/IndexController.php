<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $notifications = $request->user()
            ->notifications()
            ->limit(15)
            ->get();

        return NotificationResource::collection($notifications);
    }
}
