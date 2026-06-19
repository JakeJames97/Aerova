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

class ToggleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_marks_an_incomplete_task_complete(): void
    {
        $user = User::factory()->create();
        $task = $this->ownedTask($user);

        Sanctum::actingAs($user);

        $this->patchJson("/api/tasks/{$task->id}/toggle")
            ->assertOk()
            ->assertJsonPath('data.is_completed', true);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => true]);
    }

    #[Test]
    public function it_marks_a_complete_task_incomplete(): void
    {
        $user = User::factory()->create();
        $task = $this->ownedTask($user, true);

        Sanctum::actingAs($user);

        $this->patchJson("/api/tasks/{$task->id}/toggle")
            ->assertOk()
            ->assertJsonPath('data.is_completed', false);
    }

    #[Test]
    public function it_forbids_toggling_another_users_task(): void
    {
        $task = $this->ownedTask(User::factory()->create());

        Sanctum::actingAs(User::factory()->create());

        $this->patchJson("/api/tasks/{$task->id}/toggle")->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => false]);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $task = $this->ownedTask(User::factory()->create());

        $this->patchJson("/api/tasks/{$task->id}/toggle")->assertUnauthorized();
    }

    private function ownedTask(User $user, bool $completed = false): Task
    {
        return Task::factory()
            ->for(Destination::factory()->for(Trip::factory()->for($user)))
            ->create(['is_completed' => $completed]);
    }
}
