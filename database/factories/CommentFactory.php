<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Get a random model for comment
        $commentables = [
            \App\Models\Task::class,
            \App\Models\Team::class,
        ];

        $type = fake()->randomElement($commentables);
        $model = $type::factory();

        return [
            'content' => fake()->paragraph(),
            'user_id' => \App\Models\User::factory(),
            'commentable_type' => $type,
            'commentable_id' => $model,
        ];
    }
}
