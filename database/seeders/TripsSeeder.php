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
                    ['name' => 'Tokyo', 'tasks' => ['Book ryokan', 'Reserve teamLab tickets', 'Get JR Pass']],
                    ['name' => 'Kyoto', 'tasks' => ['Fushimi Inari early morning', 'Arashiyama bamboo grove']],
                    ['name' => 'Osaka', 'tasks' => ['Dotonbori food crawl']],
                ],
            ],
            [
                'name' => 'Italian Coast Road Trip',
                'status' => TripStatus::PROGRESS,
                'destinations' => [
                    ['name' => 'Rome', 'tasks' => ['Colosseum underground tour', 'Trastevere dinner']],
                    ['name' => 'Amalfi', 'tasks' => ['Rent a scooter', 'Path of the Gods hike']],
                ],
            ],
            [
                'name' => 'Iceland Ring Road',
                'status' => TripStatus::COMPLETED,
                'destinations' => [
                    ['name' => 'Reykjavik', 'tasks' => []],
                    ['name' => 'Vík', 'tasks' => ['Black sand beach', 'Glacier walk']],
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
                    'name' => $destData['name'],
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
