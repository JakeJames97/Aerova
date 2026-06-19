<?php

namespace App\Policies;

use App\Models\Destination;
use App\Models\Trip;
use App\Models\User;

class DestinationPolicy
{
    public function create(User $user, Trip $trip): bool
    {
        return $user->id === $trip->user_id;
    }

    public function update(User $user, Destination $destination): bool
    {
        return $user->id === $destination->trip->user_id;
    }

    public function delete(User $user, Destination $destination): bool
    {
        return $user->id === $destination->trip->user_id;
    }
}
