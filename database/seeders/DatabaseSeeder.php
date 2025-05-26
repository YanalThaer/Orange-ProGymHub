<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Courses;
use App\Models\User;
use App\Models\WorkoutProgress;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            ClubSeeder::class,
            CoachSeeder::class,
            SubscriptionPlanSeeder::class,
            UserSeeder::class,
            // WorkoutPlanSeeder::class,
            // DietPlanSeeder::class,
            // CoursesSeeder::class,
            // AttendanceSeeder::class,
            // ProgressSeeder::class,
            // UserSubscriptionSeeder::class,
            // SessionRegistrationSeeder::class,
            // CoachPaymentSeeder::class,
            // DietProgressSeeder::class,
            // QRLogSeeder::class,
            // WorkoutProgressSeeder::class,
        ]);
    }
}
