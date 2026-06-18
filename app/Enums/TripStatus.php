<?php

namespace App\Enums;

enum TripStatus: string
{
    case PLANNED = 'planned';
    case PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
