<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['todo', 'in_progress', 'on_qa', 'done', 'archive']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'due_date' => null,
            'team_id' => \App\Models\Team::factory(),
            'creator_id' => \App\Models\User::factory(),
            'assignee_id' => \App\Models\User::factory()
        ];
    }
}
