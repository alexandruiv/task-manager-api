<?php

namespace Tests\Feature\TaskController;

use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerDestroyTest extends TestCase
{
    public function test_can_destroy_created_task(): void
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->creator);

        $route = route('tasks.destroy', $task);
        $response = $this->deleteJson($route);

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', $task->toArray());
    }
}