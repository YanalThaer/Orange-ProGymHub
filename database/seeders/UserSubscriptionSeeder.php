<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\UserSubscription::create([
            'user_id' => 1,
            'club_id' => 1,
            'plan_id' => 2,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \App\Models\UserSubscription::factory(10)->create();
    }
}
