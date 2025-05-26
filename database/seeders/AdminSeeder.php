<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            Admin::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'phone_number' => '+962791234500',
                'profile_picture' => 'profile_pictures/image.jpg',
                'role' => 'super_admin',
                'last_login_at' => now()->format('Y-m-d H:i:s'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ]);
        } else {
            $this->command->info('Admin already exists, skipping creation.');
        }
    }
}
