<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed sample projects and tasks so new developers can explore the app
     * immediately after setup without manually adding data.
     */
    public function run(): void
    {
        $projects = ['Website Redesign', 'Mobile App', 'Marketing Campaign'];

        foreach ($projects as $name) {
            Project::firstOrCreate(['name' => $name]);
        }

        $sampleTasks = [
            ['name' => 'Define project scope',           'project' => 'Website Redesign'],
            ['name' => 'Design wireframes',              'project' => 'Website Redesign'],
            ['name' => 'Set up dev environment',         'project' => 'Mobile App'],
            ['name' => 'Write API specifications',       'project' => 'Mobile App'],
            ['name' => 'Create social media calendar',   'project' => 'Marketing Campaign'],
            ['name' => 'Research target audience',       'project' => 'Marketing Campaign'],
            ['name' => 'General backlog item',           'project' => null],
        ];

        foreach ($sampleTasks as $index => $data) {
            $projectId = $data['project']
                ? Project::where('name', $data['project'])->value('id')
                : null;

            Task::create([
                'name'       => $data['name'],
                'priority'   => $index + 1,
                'project_id' => $projectId,
            ]);
        }
    }
}
