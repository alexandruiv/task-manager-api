<?php 

namespace Tests\Feature\TaskController;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class TaskControllerShowTest extends TestCase
{
    public function test_can_see_created_tasks(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user, 'creator')->create();

        Sanctum::actingAs($user);
        $route = route('tasks.show', $task);
        $response = $this->getJson($route);
        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'is_done' => $task->is_done,
                    'creator_id' => $user->id,
                    'project_id' => null,
                    'created_at' => $task->created_at->jsonSerialize(),
                    'updated_at' => $task->updated_at->jsonSerialize(),
                    'status' => 'open',
                ],
            ]);
    }
    
    public function test_unauthenticated_response(): void
    {
        $task = Task::factory()->create();
    
        $route = route('tasks.show', $task);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }
    
    public function test_no_access_response(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
    
        Sanctum::actingAs($user);
        $route = route('tasks.show', $task);
        $response = $this->getJson($route);
        $response->assertNotFound();
    }
}