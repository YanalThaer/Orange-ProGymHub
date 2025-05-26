<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Coach;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutPlan>
 */
class WorkoutPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'coach_id' => Coach::inRandomOrder()->first()?->id,
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}
