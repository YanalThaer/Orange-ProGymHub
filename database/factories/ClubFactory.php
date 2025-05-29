<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    /**
     * Track used club names to ensure uniqueness
     */
    protected static array $usedNames = [];

    /**
     * Track club status counts
     */
    protected static int $inactiveCount = 0;
    protected static int $maintenanceCount = 0;
    protected static bool $statusCountsInitialized = false;

    /**
     * Available logo files for clubs
     */
    protected static array $logoFiles = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $admin = Admin::first() ?? Admin::factory()->create();

        $clubNames = [
            'Amman Fitness Center',
            'Royal Gym Jordan',
            'Petra Health Club',
            'Jordan Valley Fitness',
            'Aqaba Beach Club',
            'Irbid Sports Center',
            'Dead Sea Wellness Club',
            'Zarqa Fitness Hub',
            'Jerash Athletic Center',
            'Madaba Training Club',
            'ProGym Hub Amman',
            'FitLife Jordan',
            'PowerLift Zarqa',
            'Iron Muscle Irbid',
            'Golden Fitness Aqaba',
            'Elite Sports Club',
            'Active Life Gym',
            'Wellness World Amman',
            'Fitness Fusion Jordan',
            'Peak Performance Club',
            'Dynamic Fitness Center',
            'Synergy Health Club',
            'Vitality Gym Jordan',
            'Endurance Fitness Center',
            'Strength & Conditioning Club',
            'Flexibility & Balance Gym',
            'Agility & Speed Club',
            'Cardio & Core Fitness',
            'Strength Training Center',
            'Functional Fitness Club',
            'Body & Mind Wellness',
            'Holistic Health Gym',
            'Wellness & Recovery Center',
            'Nutrition & Fitness Club',
            'Sports Performance Gym',
            'Youth Sports Academy',
            'Senior Fitness Center',
            'Women’s Fitness Club',
            'Men’s Health Gym',
            'Family Fitness Center',
            'Community Wellness Club',
            'Corporate Wellness Gym',
            'Outdoor Adventure Club',
            'Team Sports Training Center',
            'Rehabilitation & Recovery Gym',
            'Sports Medicine & Fitness Center',
            'Personal Training & Nutrition Club',
            'Group Fitness & Wellness Center',
            'Yoga & Pilates Studio',
            'Dance & Movement Studio',
        ];

        $availableNames = array_diff($clubNames, self::$usedNames);

        if (empty($availableNames)) {
            self::$usedNames = [];
            $availableNames = $clubNames;
        }

        $name = $this->faker->randomElement($availableNames);

        self::$usedNames[] = $name;

        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+962' . $this->faker->numberBetween(7, 9) . $this->faker->numberBetween(1000000, 9999999),
            'password' => Hash::make('password'),
            'bio' => Str::limit(fake()->paragraph(), 100),
            'location' => '31.' . $this->faker->numberBetween(950000, 999999) . ',35.' . $this->faker->numberBetween(910000, 999999),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->randomElement(['Amman', 'Irbid', 'Zarqa', 'Aqaba', 'Jerash', 'Madaba', 'Salt']),
            'country' => 'Jordan',
            'description' => Str::limit(fake()->paragraph(), 300),
            'capacity' => fake()->numberBetween(50, 500),
            'has_parking' => fake()->boolean(70),
            'has_wifi' => fake()->boolean(80),
            'has_showers' => fake()->boolean(90),
            'has_lockers' => fake()->boolean(85),
            'has_pool' => fake()->boolean(40),
            'has_sauna' => fake()->boolean(30),
            'website' => fake()->url(),
            'social_media' => [
                'facebook' => 'https://facebook.com/' . $this->faker->userName(),
                'instagram' => 'https://instagram.com/' . $this->faker->userName(),
                'twitter' => 'https://twitter.com/' . $this->faker->userName(),
            ],
            'emergency_contact' => '+962' . $this->faker->numberBetween(7, 9) . $this->faker->numberBetween(1000000, 9999999),
            'status' => $this->getControlledStatus(),
            'established_date' => fake()->dateTimeBetween('-20 years', '-1 month')->format('Y-m-d'),
            'admin_id' => $admin->id,
            'logo' => $this->getRandomLogoPath(),
            'open_time' => fake()->randomElement(['06:00', '07:00', '08:00', '09:00']),
            'close_time' => fake()->randomElement(['21:00', '22:00', '23:00', '00:00']),
            'working_days' => [
                0 => true,               // Sunday (working day in Jordan)
                1 => true,               // Monday
                2 => true,               // Tuesday
                3 => true,               // Wednesday
                4 => true,               // Thursday
                5 => fake()->boolean(50), // Friday (weekend in Jordan)
                6 => fake()->boolean(30), // Saturday (weekend in Jordan)
            ],
            'special_hours' => [
                '2025-06-05' => [
                    'open' => '10:00',
                    'close' => '16:00',
                    'reason' => 'Holiday Schedule'
                ],
                '2025-05-25' => [
                    'open' => '09:00',
                    'close' => '14:00',
                    'reason' => 'Special Event'
                ],
            ],
            'verification_code' => mt_rand(100000, 999999),
            'verification_code_expires_at' => now()->addDay(),
            'email_verified' => true,
            'email_verified_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get a controlled status value to ensure specific distribution
     * 8 inactive, 10 under_maintenance, rest are active
     */
    private function getControlledStatus(): string
    {
        if (!self::$statusCountsInitialized) {
            self::$inactiveCount = 0;
            self::$maintenanceCount = 0;
            self::$statusCountsInitialized = true;
        }

        if (self::$inactiveCount < 8) {
            self::$inactiveCount++;
            return 'inactive';
        } else if (self::$maintenanceCount < 10) {
            self::$maintenanceCount++;
            return 'under_maintenance';
        } else {
            return 'active';
        }
    }

    /**
     * Get a random logo file path from storage/logos directory
     * 
     * @return string|null
     */
    private function getRandomLogoPath(): ?string
    {
        if (empty(self::$logoFiles)) {
            $logoDir = public_path('storage/logos');

            if (!file_exists($logoDir)) {
                return null;
            }

            $files = glob($logoDir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

            if (empty($files)) {
                return null;
            }

            foreach ($files as $file) {
                self::$logoFiles[] = 'logos/' . basename($file);
            }
        }

        if (empty(self::$logoFiles)) {
            return null;
        }

        return $this->faker->randomElement(self::$logoFiles);
    }
}
