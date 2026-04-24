<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------------------------------
    // Index
    // -----------------------------------------------------------------------

    public function test_task_list_page_loads(): void
    {
        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertViewIs('tasks.index');
    }

    public function test_task_list_is_filtered_by_project(): void
    {
        $project    = Project::factory()->create();
        $inProject  = Task::factory()->create(['project_id' => $project->id, 'priority' => 1]);
        $outProject = Task::factory()->create(['project_id' => null,         'priority' => 2]);

        $this->get(route('tasks.index', ['project_id' => $project->id]))
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) =>
                $tasks->contains($inProject) && ! $tasks->contains($outProject)
            );
    }

    // -----------------------------------------------------------------------
    // Store
    // -----------------------------------------------------------------------

    public function test_can_create_a_task(): void
    {
        $this->post(route('tasks.store'), ['name' => 'Write tests'])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', ['name' => 'Write tests', 'priority' => 1]);
    }

    public function test_new_task_is_appended_at_end_of_priority_list(): void
    {
        Task::factory()->create(['priority' => 1]);
        Task::factory()->create(['priority' => 2]);

        $this->post(route('tasks.store'), ['name' => 'Third task']);

        $this->assertDatabaseHas('tasks', ['name' => 'Third task', 'priority' => 3]);
    }

    public function test_task_name_is_required(): void
    {
        $this->post(route('tasks.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_can_create_task_with_project(): void
    {
        $project = Project::factory()->create();

        $this->post(route('tasks.store'), [
            'name'       => 'Task with project',
            'project_id' => $project->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'name'       => 'Task with project',
            'project_id' => $project->id,
        ]);
    }

    // -----------------------------------------------------------------------
    // Update
    // -----------------------------------------------------------------------

    public function test_can_update_a_task_name(): void
    {
        $task = Task::factory()->create(['name' => 'Old name', 'priority' => 1]);

        $this->patch(route('tasks.update', $task), ['name' => 'New name'])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'New name']);
    }

    public function test_can_update_a_task_project(): void
    {
        $project = Project::factory()->create();
        $task    = Task::factory()->create(['priority' => 1, 'project_id' => null]);

        $this->patch(route('tasks.update', $task), [
            'name'       => $task->name,
            'project_id' => $project->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'project_id' => $project->id]);
    }

    public function test_update_requires_a_name(): void
    {
        $task = Task::factory()->create(['priority' => 1]);

        $this->patch(route('tasks.update', $task), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    // -----------------------------------------------------------------------
    // Destroy
    // -----------------------------------------------------------------------

    public function test_can_delete_a_task(): void
    {
        $task = Task::factory()->create(['priority' => 1]);

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_priorities_compact_after_deletion(): void
    {
        $first  = Task::factory()->create(['priority' => 1]);
        $second = Task::factory()->create(['priority' => 2]);
        $third  = Task::factory()->create(['priority' => 3]);

        $this->delete(route('tasks.destroy', $second));

        $this->assertDatabaseHas('tasks', ['id' => $first->id, 'priority' => 1]);
        $this->assertDatabaseHas('tasks', ['id' => $third->id, 'priority' => 2]);
    }

    // -----------------------------------------------------------------------
    // Reorder
    // -----------------------------------------------------------------------

    public function test_can_reorder_tasks(): void
    {
        $first  = Task::factory()->create(['priority' => 1]);
        $second = Task::factory()->create(['priority' => 2]);
        $third  = Task::factory()->create(['priority' => 3]);

        $this->postJson(route('tasks.reorder'), [
            'tasks' => [$third->id, $first->id, $second->id],
        ])->assertOk();

        $this->assertDatabaseHas('tasks', ['id' => $third->id,  'priority' => 1]);
        $this->assertDatabaseHas('tasks', ['id' => $first->id,  'priority' => 2]);
        $this->assertDatabaseHas('tasks', ['id' => $second->id, 'priority' => 3]);
    }

    public function test_reorder_requires_at_least_one_task_id(): void
    {
        $this->postJson(route('tasks.reorder'), ['tasks' => []])
            ->assertUnprocessable();
    }

    public function test_reorder_rejects_nonexistent_task_id(): void
    {
        $this->postJson(route('tasks.reorder'), ['tasks' => [99999]])
            ->assertUnprocessable();
    }
}
