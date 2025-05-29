<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPlan>
 */
class SubscriptionPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $club = Club::inRandomOrder()->first() ?? Club::factory()->create();

        $planTypes = [
            'monthly' => [
                'names' => ['Monthly Standard', 'Monthly Basic', 'Monthly Access', 'Monthly Fitness', 'Monthly Pass'],
                'price_range' => [25, 50],
                'duration_days' => 30
            ],
            'quarterly' => [
                'names' => ['Quarterly Premium', 'Quarterly Access', 'Three-Month Pass', 'Seasonal Membership', 'Quarter Plus'],
                'price_range' => [60, 120],
                'duration_days' => 90
            ],
            'yearly' => [
                'names' => ['Annual Membership', 'Yearly Premium', 'Full Year Access', 'Gold Annual', '12-Month Plan'],
                'price_range' => [200, 400],
                'duration_days' => 365
            ],
            'custom' => [
                'names' => ['Flex Plan', 'Custom Membership', 'Personalized Plan', 'Tailored Access', 'Special Membership'],
                'price_range' => [40, 300],
                'duration_days' => null
            ],
        ];

        $planType = $this->faker->randomElement(array_keys($planTypes));
        $planDetails = $planTypes[$planType];

        $duration = $planType === 'custom'
            ? $this->faker->randomElement([7, 14, 21, 45, 60, 120, 180])
            : $planDetails['duration_days'];

        return [
            'name' => $this->faker->randomElement($planDetails['names']),
            'price' => $this->faker->numberBetween($planDetails['price_range'][0], $planDetails['price_range'][1]),
            'duration_days' => $duration,
            'type' => $planType,
            'is_active' => true,
            'club_id' => $club->id,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SubscriptionPlan $plan) {})->afterCreating(function (SubscriptionPlan $plan) {});
    }

    /**
     * Set the subscription plan as active.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Set the subscription plan as inactive.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Create a subscription plan of a specific type
     *
     * @param string $type One of: monthly, quarterly, yearly, custom
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function ofType(string $type)
    {
        if (!in_array($type, ['monthly', 'quarterly', 'yearly', 'custom'])) {
            throw new \InvalidArgumentException("Type must be one of: monthly, quarterly, yearly, custom");
        }

        return $this->state(function (array $attributes) use ($type) {
            $duration = match ($type) {
                'monthly' => 30,
                'quarterly' => 90,
                'yearly' => 365,
                'custom' => $this->faker->randomElement([7, 14, 21, 45, 60, 120, 180]),
                default => 30,
            };

            return [
                'type' => $type,
                'duration_days' => $duration,
            ];
        });
    }
}
