<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Coach;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $maleNames = ['Ahmad', 'Mohammad', 'Omar', 'Ali', 'Khaled', 'Yanal', 'Zaid', 'Ibrahim', 'Rami', 'Sami' , 'Khalil' , 'Alaa' , 'Yamen' , 'Ayham' , 'Zaid' , 'Basel' , 'Malek' , 'Hassan' , 'Tamer' , 'Fadi' , 'Othman' , 'Yousef' , 'Anas' , 'Tariq' , 'Ziad' , 'Hussein' , 'Nabil' , 'Adel' , 'Firas' , 'Rami' , 'Zaki' , 'Hisham'];
        $femaleNames = ['Reem', 'Sarah', 'Hiba', 'Noor', 'Dana', 'Layla', 'Hala', 'Maya', 'Yasmin' , 'Ola' , 'Mariam' , 'Rose' , 'Dalia' , 'Nour' , 'Rana' , 'Samar' , 'Hanan' , 'Mariam' , 'Amani' , 'Nadia' , 'Ranya' , 'Dania' , 'Dareen' , 'Lina' , 'Ranya' , 'Hala' , 'Nisreen' , 'Sawsan' , 'Maha' , 'Jana' , 'Riham' , 'Hind'];
        $lastNames = ['Abdullah', 'Mahmoud', 'Al-Hassan', 'Al-Qasem', 'Haddad', 'Khatib', 'Nassar', 'Younis', 'Saleh', 'Mansour' , 'Abu Zaid' , 'Al-Masri' , 'Al-Jabari' , 'Al-Khalidi' , 'Al-Saleh' , 'Al-Ali' , 'Al-Hamadi' , 'Al-Qudsi' , 'Al-Sharif' , 'Al-Najjar'];
        
        $name = $gender === 'male' 
            ? $this->faker->randomElement($maleNames) . ' ' . $this->faker->randomElement($lastNames)
            : $this->faker->randomElement($femaleNames) . ' ' . $this->faker->randomElement($lastNames);
            
        $height = $this->faker->numberBetween(155, 190);
        $baseWeight = ($height - 100) * ($gender === 'male' ? 0.9 : 0.85);
        $weight = round($baseWeight * $this->faker->randomFloat(1, 0.85, 1.25));
        
        $targetWeight = $weight;
        $goal = $this->faker->randomElement(['weight_loss', 'muscle_gain', 'maintenance', 'strength', 'flexibility']);
        if ($goal === 'weight_loss') {
            $targetWeight = round($weight * $this->faker->randomFloat(1, 0.8, 0.95));
        } elseif ($goal === 'muscle_gain') {
            $targetWeight = round($weight * $this->faker->randomFloat(1, 1.05, 1.15));
        }
        
        $bmi = round($weight / (($height / 100) * ($height / 100)), 1);
            
        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'join_date' => $this->faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d'),
            'goal' => $goal,
            
            'phone_number' => '+962' . $this->faker->numberBetween(7, 9) . $this->faker->numberBetween(1000000, 9999999),
            'date_of_birth' => $this->faker->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
            'gender' => $gender,
            
            'height_cm' => $height,
            'weight_kg' => $weight,
            'target_weight_kg' => $targetWeight,
            'bmi' => $bmi,
            'body_fat_percentage' => $this->faker->randomFloat(1, ($gender === 'male' ? 8 : 15), ($gender === 'male' ? 25 : 32)),
            
            'health_conditions' => $this->faker->optional(0.2)->randomElement(['None', 'Hypertension', 'Diabetes Type 2', 'Asthma', 'Back pain']),
            'injuries' => $this->faker->optional(0.15)->randomElement(['None', 'Knee injury', 'Shoulder pain', 'Ankle sprain', 'Lower back strain']),
            'allergies' => $this->faker->optional(0.1)->randomElement(['None', 'Nuts', 'Dairy', 'Gluten', 'Seafood']),
            'medications' => $this->faker->optional(0.1)->randomElement(['None', 'Blood pressure medication', 'Anti-inflammatory', 'Supplements']),
            
            'fitness_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'training_days_per_week' => $this->faker->numberBetween(1, 7),
            'preferred_training_time' => $this->faker->randomElement(['morning', 'afternoon', 'evening']),
            'preferred_workout_duration' => $this->faker->numberBetween(30, 120),
            'exercise_preferences' => $this->faker->randomElement(['Weightlifting', 'Cardio', 'HIIT', 'Yoga', 'Swimming', 'Running', 'Boxing', 'Functional training']),
            'exercise_dislikes' => $this->faker->optional(0.6)->randomElement(['Long-distance running', 'Heavy weights', 'Burpees', 'Push-ups', 'Swimming']),
            
            'diet_preference' => $this->faker->randomElement(['no_restriction', 'vegetarian', 'vegan', 'keto', 'paleo', 'mediterranean', 'other']),
            'meals_per_day' => $this->faker->numberBetween(3, 6),
            'food_preferences' => $this->faker->optional(0.7)->randomElement(['Protein-rich foods', 'Fruits', 'Vegetables', 'Traditional Arabic food', 'Low-carb options', 'Healthy snacks']),
            'food_dislikes' => $this->faker->optional(0.5)->randomElement(['Fast food', 'Processed sugar', 'Red meat', 'Dairy', 'Spicy food']),
            
            'club_id' => function() {
                return Club::inRandomOrder()->first()?->id;
            },
            'coach_id' => function(array $attributes) {
                return Coach::where('club_id', $attributes['club_id'])
                    ->inRandomOrder()
                    ->first()?->id;
            }
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
