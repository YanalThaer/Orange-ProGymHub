<?php

namespace Database\Factories;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoachPayment>
 */
class CoachPaymentFactory extends Factory
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
            'user_id' => User::inRandomOrder()->first()?->id,
            'amount' => $this->faker->numberBetween(20, 200),
            'payment_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'payment_method' => $this->faker->randomElement(['Credit Card', 'PayPal', 'Bank Transfer']),
            'payment_status' => $this->faker->randomElement(['Completed', 'Pending', 'Failed']),
        ];
    }
}
