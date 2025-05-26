<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'phone_number' => '+962 7' . fake()->numerify('########'),
            'profile_picture' => 'profile_pictures/image.jpg',
            'role' => 'super_admin',
            'last_login_at' => now()->format('Y-m-d H:i:s'),
            'email_verified_at' => now()->format('Y-m-d H:i:s'),
            'remember_token' => Str::random(10),
        ];
    }
}
