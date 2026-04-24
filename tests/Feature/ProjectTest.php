<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_project(): void
    {
        $this->post(route('projects.store'), ['name' => 'My Project'])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', ['name' => 'My Project']);
    }

    public function test_project_name_is_required(): void
    {
        $this->post(route('projects.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_project_name_must_be_unique(): void
    {
        Project::factory()->create(['name' => 'Duplicate']);

        $this->post(route('projects.store'), ['name' => 'Duplicate'])
            ->assertSessionHasErrors('name');
    }

    public function test_deleting_a_project_cascades_to_its_tasks(): void
    {
        $project = Project::factory()->create();
        $task    = Task::factory()->create(['project_id' => $project->id, 'priority' => 1]);

        $this->delete(route('projects.destroy', $project))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('tasks',    ['id' => $task->id]);
    }
}
