<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Progress>
 */
class ProgressFactory extends Factory
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
            'weight' => $this->faker->randomFloat(2, 50, 120),
            'body_fat' => $this->faker->randomFloat(2, 10, 30),
            'notes' => $this->faker->sentence(),
        ];
    }
}
