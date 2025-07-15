<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idea_id' => $this->faker->numberBetween(1, 100),
            'user_id' => $this->faker->numberBetween(1, 20),
        ];
    }

    /**
     * Create a vote for a specific idea and user
     */
    public function forIdeaAndUser(int $ideaId, int $userId): static
    {
        return $this->state(function (array $attributes) use ($ideaId, $userId) {
            return [
                'idea_id' => $ideaId,
                'user_id' => $userId,
            ];
        });
    }
}
