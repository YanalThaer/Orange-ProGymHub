<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutProgress>
 */
class WorkoutProgressFactory extends Factory
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
            'workout_id' => WorkoutPlan::inRandomOrder()->first()?->id,
            'status' => $this->faker->randomElement(['Completed', 'In Progress']),
        ];
    }
}
