<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QRLog>
 */
class QRLogFactory extends Factory
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
            'location' => $this->faker->randomElement(['valid', 'invalid']),
            'scan_time' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
