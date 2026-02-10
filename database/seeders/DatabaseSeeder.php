<?php
namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add random 20 users
        $users = \App\Models\User::factory(20)->create();

        $teams = \App\Models\Team::factory(5)
            ->hasAttached($users->random(5)) // Many-to-Many: add users to the team
            ->create();

        foreach ($teams as $team) {
            $tasks = \App\Models\Task::factory(10)->create([
                'status'   => fake()->randomElement([Task::STATUS_TODO, Task::STATUS_IN_PROGRESS, Task::STATUS_DONE]),
                'priority' => fake()->randomElement([Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH]),
                'team_id' => $team->id,
                'creator_id' => $users->random()->id,
                'assignee_id' => $users->random()->id
            ]);

            // Add comments to tasks
            foreach ($tasks->random(3) as $task) {
                \App\Models\Comment::factory()->create([
                    'commentable_id' => $task->id,
                    'commentable_type' => \App\Models\Task::class,
                    'user_id' => $users->random()->id,
                ]);
            }
        }
    }
}
