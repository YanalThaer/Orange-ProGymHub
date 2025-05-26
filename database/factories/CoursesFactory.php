<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Coach;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\courses>
 */
class CoursesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'coach_id' => Coach::inRandomOrder()->first()?->id,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_time' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'end_time' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'max_participants' => $this->faker->numberBetween(5, 50),
            'participants_count' => 0,
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
