<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->sentence(4, true),
            'priority'   => $this->faker->numberBetween(1, 100),
            'project_id' => null,
        ];
    }
}
