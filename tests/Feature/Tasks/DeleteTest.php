<?php

namespace Tests\Feature\Tasks;

use App\Models\Destination;
use App\Models\Task;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_an_owned_task(): void
    {
        $user = User::factory()->create();
        $task = $this->ownedTask($user);

        Sanctum::actingAs($user);

        $this->deleteJson("/api/tasks/{$task->id}")->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_forbids_deleting_another_users_task(): void
    {
        $task = $this->ownedTask(User::factory()->create());

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/tasks/{$task->id}")->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $task = $this->ownedTask(User::factory()->create());

        $this->deleteJson("/api/tasks/{$task->id}")->assertUnauthorized();
    }

    private function ownedTask(User $user): Task
    {
        return Task::factory()
            ->for(Destination::factory()->for(Trip::factory()->for($user)))
            ->create();
    }
}
