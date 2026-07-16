<?php

namespace App\Enums;

enum TransportType: string
{
    case FLIGHT = 'flight';
    case TRAIN = 'train';
    case CAR = 'car';
    case OTHER = 'other';
}
