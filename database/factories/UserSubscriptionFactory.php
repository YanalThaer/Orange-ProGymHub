<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSubscription>
 */
class UserSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $startDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(30, 365) . ' days');
        
        return [
            'user_id' => $user?->id,
            'plan_id' => SubscriptionPlan::inRandomOrder()->first()?->id,
            'club_id' => $user?->club_id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'payment_status' => $this->faker->randomElement(['paid', 'pending', 'failed']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer', 'cash']),
        ];
    }
}
