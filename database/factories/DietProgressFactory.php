<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\DietPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DietProgress>
 */
class DietProgressFactory extends Factory
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
            'diet_id' => DietPlan::inRandomOrder()->first()?->id,
            'status' => $this->faker->randomElement(['Completed', 'In Progress']),
        ];
    }
}
