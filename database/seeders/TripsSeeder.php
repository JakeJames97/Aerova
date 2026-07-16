<?php

namespace Database\Seeders;

use App\Enums\TransportType;
use App\Enums\TripStatus;
use App\Models\Destination;
use App\Models\Task;
use App\Models\Transport;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;

class TripsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create();

        $trips = [
            [
                'name' => 'Japan in Autumn',
                'status' => TripStatus::PLANNED,
                'is_public' => true,
                'destinations' => [
                    [
                        'city' => 'Tokyo',
                        'country_code' => 'JP',
                        'budget' => 2500,
                        'tasks' => ['Book ryokan', 'Reserve teamLab tickets', 'Get JR Pass'],
                        'transports' => [
                            [
                                'type' => TransportType::FLIGHT,
                                'from' => 'Edinburgh Airport',
                                'to' => 'Heathrow Airport',
                                'identifier' => 'BA1440',
                                'departure_at' => '2026-10-03 06:20:00',
                                'arrival_at' => '2026-10-03 07:50:00',
                                'price' => 8900,
                                'airline' => 'British Airways',
                                'from_iata' => 'EDI',
                                'to_iata' => 'LHR',
                            ],
                            [
                                'type' => TransportType::FLIGHT,
                                'from' => 'Heathrow Airport',
                                'to' => 'Haneda Airport',
                                'identifier' => 'BA5',
                                'departure_at' => '2026-10-03 13:10:00',
                                'arrival_at' => '2026-10-04 09:15:00',
                                'price' => 62400,
                                'airline' => 'British Airways',
                                'from_iata' => 'LHR',
                                'to_iata' => 'HND',
                            ],
                        ],
                    ],
                    [
                        'city' => 'Kyoto',
                        'country_code' => 'JP',
                        'budget' => 1800,
                        'tasks' => ['Fushimi Inari early morning', 'Arashiyama bamboo grove'],
                        'transports' => [
                            [
                                'type' => TransportType::TRAIN,
                                'from' => 'Tokyo Station',
                                'to' => 'Kyoto Station',
                                'identifier' => 'Nozomi 21',
                                'departure_at' => '2026-10-09 09:30:00',
                                'arrival_at' => '2026-10-09 11:45:00',
                                'price' => 7800,
                            ],
                        ],
                    ],
                    [
                        'city' => 'Osaka',
                        'country_code' => 'JP',
                        'budget' => 1500,
                        'tasks' => ['Dotonbori food crawl'],
                        'transports' => [
                            [
                                'type' => TransportType::TRAIN,
                                'from' => 'Kyoto Station',
                                'to' => 'Osaka Station',
                                'identifier' => 'Special Rapid 3284',
                                'departure_at' => '2026-10-13 10:15:00',
                                'arrival_at' => '2026-10-13 10:44:00',
                                'price' => 580,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Italian Coast Road Trip',
                'status' => TripStatus::PROGRESS,
                'is_public' => true,
                'destinations' => [
                    [
                        'city' => 'Rome',
                        'country_code' => 'IT',
                        'budget' => 2000,
                        'tasks' => ['Colosseum underground tour', 'Trastevere dinner'],
                        'transports' => [
                            [
                                'type' => TransportType::FLIGHT,
                                'from' => 'Edinburgh Airport',
                                'to' => 'Rome Ciampino Airport',
                                'identifier' => 'FR9012',
                                'departure_at' => '2026-05-11 07:05:00',
                                'arrival_at' => '2026-05-11 11:00:00',
                                'price' => 11200,
                                'airline' => 'Ryanair',
                                'from_iata' => 'EDI',
                                'to_iata' => 'CIA',
                            ],
                        ],
                    ],
                    [
                        'city' => 'Amalfi',
                        'country_code' => 'IT',
                        'budget' => 2200,
                        'tasks' => ['Rent a scooter', 'Path of the Gods hike'],
                        'transports' => [
                            [
                                'type' => TransportType::CAR,
                                'from' => 'Rome',
                                'to' => 'Amalfi',
                                'departure_at' => '2026-05-16 09:00:00',
                                'arrival_at' => '2026-05-16 13:30:00',
                                'price' => 14000,
                            ],
                        ],
                    ],
                    [
                        'city' => 'Naples',
                        'country_code' => 'IT',
                        'budget' => 900,
                        'tasks' => ['Pizza at Da Michele', 'Pompeii day trip'],
                        'transports' => [
                            [
                                'type' => TransportType::CAR,
                                'from' => 'Amalfi',
                                'to' => 'Naples',
                                'departure_at' => '2026-05-20 11:00:00',
                                'arrival_at' => '2026-05-20 12:45:00',
                                'price' => 0,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Iceland Ring Road',
                'status' => TripStatus::COMPLETED,
                'is_public' => false,
                'destinations' => [
                    [
                        'city' => 'Reykjavik',
                        'country_code' => 'IS',
                        'budget' => 1600,
                        'tasks' => [],
                        'transports' => [
                            [
                                'type' => TransportType::FLIGHT,
                                'from' => 'Edinburgh Airport',
                                'to' => 'Keflavík International Airport',
                                'identifier' => 'FI431',
                                'departure_at' => '2025-09-02 13:40:00',
                                'arrival_at' => '2025-09-02 15:25:00',
                                'price' => 19800,
                                'airline' => 'Icelandair',
                                'from_iata' => 'EDI',
                                'to_iata' => 'KEF',
                            ],
                        ],
                    ],
                    [
                        'city' => 'Vík',
                        'country_code' => 'IS',
                        'budget' => 1200,
                        'tasks' => ['Black sand beach', 'Glacier walk'],
                        'transports' => [
                            [
                                'type' => TransportType::CAR,
                                'from' => 'Reykjavik',
                                'to' => 'Vík',
                                'departure_at' => '2025-09-05 10:00:00',
                                'arrival_at' => '2025-09-05 12:30:00',
                                'price' => 0,
                            ],
                        ],
                    ],
                    [
                        'city' => 'Höfn',
                        'country_code' => 'IS',
                        'budget' => 800,
                        'tasks' => ['Jökulsárlón glacier lagoon', 'Langoustine at Pakkhús'],
                        'transports' => [
                            [
                                'type' => TransportType::CAR,
                                'from' => 'Vík',
                                'to' => 'Höfn',
                                'departure_at' => '2025-09-08 09:30:00',
                                'arrival_at' => '2025-09-08 13:00:00',
                                'price' => 0,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Long Weekend in Lisbon',
                'status' => TripStatus::COMPLETED,
                'is_public' => true,
                'destinations' => [
                    [
                        'city' => 'Lisbon',
                        'country_code' => 'PT',
                        'budget' => 700,
                        'tasks' => ['Tram 28 early', 'Time Out Market', 'Belém pastéis'],
                        'transports' => [
                            [
                                'type' => TransportType::FLIGHT,
                                'from' => 'Edinburgh Airport',
                                'to' => 'Humberto Delgado Airport',
                                'identifier' => 'U28904',
                                'departure_at' => '2025-04-24 09:50:00',
                                'arrival_at' => '2025-04-24 13:05:00',
                                'price' => 8600,
                                'airline' => 'easyJet',
                                'from_iata' => 'EDI',
                                'to_iata' => 'LIS',
                            ],
                        ],
                    ],
                    [
                        'city' => 'Sintra',
                        'country_code' => 'PT',
                        'budget' => 200,
                        'tasks' => ['Pena Palace tickets in advance'],
                        'transports' => [
                            [
                                'type' => TransportType::TRAIN,
                                'from' => 'Rossio Station',
                                'to' => 'Sintra Station',
                                'identifier' => 'CP Urbano',
                                'departure_at' => '2025-04-26 08:41:00',
                                'arrival_at' => '2025-04-26 09:21:00',
                                'price' => 250,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Norwegian Fjords',
                'status' => TripStatus::PLANNED,
                'is_public' => true,
                'destinations' => [
                    [
                        'city' => 'Bergen',
                        'country_code' => 'NO',
                        'budget' => 1400,
                        'tasks' => ['Fløibanen funicular', 'Bryggen wharf walk'],
                        'transports' => [
                            [
                                'type' => TransportType::FLIGHT,
                                'from' => 'Edinburgh Airport',
                                'to' => 'Bergen Airport',
                                'identifier' => 'WF381',
                                'departure_at' => '2026-06-18 11:30:00',
                                'arrival_at' => '2026-06-18 14:10:00',
                                'price' => 14700,
                                'airline' => 'Widerøe',
                                'from_iata' => 'EDI',
                                'to_iata' => 'BGO',
                            ],
                        ],
                    ],
                    [
                        'city' => 'Flåm',
                        'country_code' => 'NO',
                        'budget' => 1100,
                        'tasks' => ['Flåm Railway', 'Nærøyfjord cruise'],
                        'transports' => [
                            [
                                'type' => TransportType::TRAIN,
                                'from' => 'Bergen Station',
                                'to' => 'Myrdal Station',
                                'identifier' => 'Bergensbanen 62',
                                'departure_at' => '2026-06-21 08:39:00',
                                'arrival_at' => '2026-06-21 10:52:00',
                                'price' => 4200,
                            ],
                            [
                                'type' => TransportType::TRAIN,
                                'from' => 'Myrdal Station',
                                'to' => 'Flåm Station',
                                'identifier' => 'Flåmsbana 4',
                                'departure_at' => '2026-06-21 11:10:00',
                                'arrival_at' => '2026-06-21 12:05:00',
                                'price' => 5500,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Berlin for the Weekend',
                'status' => TripStatus::PROGRESS,
                'is_public' => false,
                'destinations' => [
                    [
                        'city' => 'Berlin',
                        'country_code' => 'DE',
                        'budget' => 600,
                        'tasks' => ['Book Berghain contingency plan', 'Museum Island pass'],
                        'transports' => [
                            [
                                'type' => TransportType::FLIGHT,
                                'from' => 'Edinburgh Airport',
                                'to' => 'Berlin Brandenburg Airport',
                                'identifier' => 'U22287',
                                'departure_at' => '2026-07-24 06:40:00',
                                'arrival_at' => '2026-07-24 09:35:00',
                                'price' => 6400,
                                'airline' => 'easyJet',
                                'from_iata' => 'EDI',
                                'to_iata' => 'BER',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($trips as $tripData) {
            $trip = Trip::factory()->for($user)->create([
                'name' => $tripData['name'],
                'status' => $tripData['status'],
                'is_public' => $tripData['is_public'],
            ]);

            foreach ($tripData['destinations'] as $destData) {
                $destination = Destination::factory()->for($trip)->create([
                    'city' => $destData['city'],
                    'country_code' => $destData['country_code'],
                    'budget' => $destData['budget'],
                ]);

                foreach ($destData['tasks'] as $taskTitle) {
                    Task::factory()->for($destination)->create([
                        'title' => $taskTitle,
                        'is_completed' => $tripData['status'] === TripStatus::COMPLETED,
                    ]);
                }

                foreach ($destData['transports'] as $transportData) {
                    Transport::factory()->for($destination)->create($transportData);
                }
            }
        }
    }
}
