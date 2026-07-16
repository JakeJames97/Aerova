<?php

namespace App\Policies;

use App\Models\Destination;
use App\Models\Transport;
use App\Models\User;

class TransportPolicy
{
    public function create(User $user, Destination $destination): bool
    {
        return $user->id === $destination->trip->user_id;
    }

    public function update(User $user, Transport $transport): bool
    {
        return $user->id === $transport->destination->trip->user_id;
    }

    public function delete(User $user, Transport $transport): bool
    {
        return $user->id === $transport->destination->trip->user_id;
    }
}
