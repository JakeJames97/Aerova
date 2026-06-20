<?php

namespace Database\Seeders;

use App\Enums\TripStatus;
use App\Models\Destination;
use App\Models\Task;
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
                'destinations' => [
                    [
                        'city' => 'Tokyo',
                        'country_code' => 'JP',
                        'budget' => 2500,
                        'tasks' => [
                            'Book ryokan',
                            'Reserve teamLab tickets',
                            'Get JR Pass',
                        ],
                    ],
                    [
                        'city' => 'Kyoto',
                        'country_code' => 'JP',
                        'budget' => 1800,
                        'tasks' => [
                            'Fushimi Inari early morning',
                            'Arashiyama bamboo grove',
                        ],
                    ],
                    [
                        'city' => 'Osaka',
                        'country_code' => 'JP',
                        'budget' => 1500,
                        'tasks' => [
                            'Dotonbori food crawl',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Italian Coast Road Trip',
                'status' => TripStatus::PROGRESS,
                'destinations' => [
                    [
                        'city' => 'Rome',
                        'country_code' => 'IT',
                        'budget' => 2000,
                        'tasks' => [
                            'Colosseum underground tour',
                            'Trastevere dinner',
                        ],
                    ],
                    [
                        'city' => 'Amalfi',
                        'country_code' => 'IT',
                        'budget' => 2200,
                        'tasks' => [
                            'Rent a scooter',
                            'Path of the Gods hike',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Iceland Ring Road',
                'status' => TripStatus::COMPLETED,
                'destinations' => [
                    [
                        'city' => 'Reykjavik',
                        'country_code' => 'IS',
                        'budget' => 1600,
                        'tasks' => [],
                    ],
                    [
                        'city' => 'Vík',
                        'country_code' => 'IS',
                        'budget' => 1200,
                        'tasks' => [
                            'Black sand beach',
                            'Glacier walk',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($trips as $tripData) {
            $trip = Trip::factory()->for($user)->create([
                'name' => $tripData['name'],
                'status' => $tripData['status'],
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
            }
        }
    }
}
