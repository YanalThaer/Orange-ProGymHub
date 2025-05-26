<?php

namespace Database\Factories;

use App\Models\Courses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionRegistration>
 */
class SessionRegistrationFactory extends Factory
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
            'session_id' => Courses::inRandomOrder()->first()?->id,
            'registration_time' => $this->faker->dateTime(),
            'payment_status' => $this->faker->randomElement(['Completed', 'Pending']),
            'payment_method' => $this->faker->randomElement(['Credit Card', 'PayPal', 'Bank Transfer']),
        ];
    }
}
