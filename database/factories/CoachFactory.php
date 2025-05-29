<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Club;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coach>
 */
class CoachFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $noClubCount = 0;

        static $initialized = false;
        if (!$initialized) {
            $noClubCount = 0;
            $initialized = true;
        }

        $isClubCoach = ($noClubCount < 30) ? $this->faker->boolean(50) : true;

        if (!$isClubCoach) {
            $noClubCount++;
        }

        $maleNames = ['Ahmad', 'Mohammad', 'Omar', 'Ali', 'Khaled', 'Yanal', 'Zaid', 'Ibrahim', 'Rami', 'Sami', 'Khalil', 'Alaa', 'Yamen', 'Ayham', 'Zaid', 'Basel', 'Malek', 'Hassan', 'Tamer', 'Fadi', 'Othman', 'Yousef', 'Anas', 'Tariq', 'Ziad', 'Hussein', 'Nabil', 'Adel', 'Firas', 'Rami', 'Zaki', 'Hisham'];
        $femaleNames = ['Reem', 'Sarah', 'Hiba', 'Noor', 'Dana', 'Layla', 'Hala', 'Maya', 'Yasmin', 'Ola', 'Mariam', 'Rose', 'Dalia', 'Nour', 'Rana', 'Samar', 'Hanan', 'Mariam', 'Amani', 'Nadia', 'Ranya', 'Dania', 'Dareen', 'Lina', 'Ranya', 'Hala', 'Nisreen', 'Sawsan', 'Maha', 'Jana', 'Riham', 'Hind'];
        $lastNames = ['Abdullah', 'Mahmoud', 'Al-Hassan', 'Al-Qasem', 'Haddad', 'Khatib', 'Nassar', 'Younis', 'Saleh', 'Mansour', 'Abu Zaid', 'Al-Masri', 'Al-Jabari', 'Al-Khalidi', 'Al-Saleh', 'Al-Ali', 'Al-Hamadi', 'Al-Qudsi', 'Al-Sharif', 'Al-Najjar'];

        $useMaleName = $this->faker->boolean(60);

        $gender = $useMaleName ? 'male' : 'female';

        $name = $useMaleName
            ? $this->faker->randomElement($maleNames) . ' ' . $this->faker->randomElement($lastNames)
            : $this->faker->randomElement($femaleNames) . ' ' . $this->faker->randomElement($lastNames);

        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+962' . $this->faker->numberBetween(7, 9) . $this->faker->numberBetween(1000000, 9999999),
            'location' => $this->faker->randomElement(['Amman', 'Irbid', 'Zarqa', 'Aqaba', 'Jerash', 'Madaba']) . ', Jordan',
            'bio' => $this->faker->paragraph(),
            'password' => bcrypt('password'),
            'club_id' => Club::inRandomOrder()->first()?->id,

            'gender' => $gender,
            'profile_image' => 'coach-profiles/' . ($useMaleName ? 'man' : 'female') . '/' . $this->faker->numberBetween(1, 15) . '.jpg',
            'experience_years' => $this->faker->numberBetween(1, 15),
            'certifications' => $this->faker->boolean(80) ? json_encode([
                $this->faker->randomElement(['ACE', 'NASM', 'ISSA', 'ACSM', 'NSCA', 'Jordan Fitness Association']),
                $this->faker->randomElement(['CPR Certified', 'First Aid', 'Nutrition Coach', 'Sports Rehabilitation'])
            ]) : null,
            'specializations' => json_encode([
                $this->faker->randomElement(['Weight Loss', 'Muscle Building', 'Functional Training', 'Athletic Performance']),
                $this->faker->randomElement(['CrossFit', 'Yoga', 'Pilates', 'HIIT', 'Strength Training', 'Bodybuilding'])
            ]),

            'employment_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contractor']),
            'working_hours' => $this->getRandomWorkingHours(),
            'verification_code' => mt_rand(100000, 999999),
            'email_verified_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Generate random working hours schedule
     *
     * @return string JSON encoded working hours
     */
    private function getRandomWorkingHours(): string
    {
        $scheduleTypes = [
            [
                'monday' => ['09:00-12:00'],
                'wednesday' => ['16:00-20:00'],
                'friday' => ['10:00-14:00'],
            ],

            [
                'sunday' => ['07:00-09:00', '18:00-20:00'],
                'tuesday' => ['07:00-09:00', '18:00-20:00'],
                'thursday' => ['07:00-09:00', '18:00-20:00'],
                'saturday' => ['10:00-14:00'],
            ],

            [
                'sunday' => ['16:00-18:00'],
                'monday' => ['10:00-12:00', '20:00-22:00'],
                'tuesday' => ['14:00-16:00'],
                'wednesday' => ['20:00-22:00'],
                'thursday' => ['10:00-12:00'],
                'friday' => ['14:00-16:00'],
            ],

            [
                'sunday' => ['07:00-12:00'],
                'monday' => ['07:00-12:00'],
                'tuesday' => ['07:00-12:00'],
                'wednesday' => ['07:00-12:00'],
                'thursday' => ['07:00-12:00'],
                'saturday' => $this->faker->boolean(30) ? ['09:00-13:00'] : [],
            ],

            [
                'sunday' => ['16:00-21:00'],
                'monday' => ['16:00-21:00'],
                'tuesday' => ['16:00-21:00'],
                'wednesday' => ['16:00-21:00'],
                'thursday' => ['16:00-21:00'],
                'friday' => $this->faker->boolean(20) ? ['16:00-20:00'] : [],
            ],

            [
                'sunday' => ['09:00-12:00', '17:00-20:00'],
                'monday' => ['09:00-12:00', '17:00-20:00'],
                'wednesday' => ['09:00-12:00', '17:00-20:00'],
                'thursday' => ['09:00-12:00', '17:00-20:00'],
                'saturday' => $this->faker->boolean(40) ? ['10:00-15:00'] : [],
            ],

            [
                'tuesday' => ['14:00-20:00'],
                'wednesday' => ['14:00-20:00'],
                'thursday' => ['14:00-20:00'],
                'friday' => ['10:00-18:00'],
                'saturday' => ['10:00-18:00'],
            ],

            [
                'sunday' => ['10:00-16:00'],
                'monday' => ['10:00-16:00'],
                'tuesday' => ['10:00-16:00'],
                'wednesday' => ['10:00-16:00'],
                'thursday' => ['10:00-16:00'],
            ],

            [
                $this->faker->randomElement(['sunday', 'monday', 'tuesday']) => ['09:00-15:00'],
                $this->faker->randomElement(['wednesday', 'thursday']) => ['09:00-15:00'],
                $this->faker->randomElement(['friday', 'saturday']) => ['09:00-15:00'],
            ],

            [
                'sunday' => ['09:00-20:00'],
                'monday' => ['09:00-20:00'],
                'tuesday' => ['09:00-20:00'],
                'wednesday' => ['09:00-20:00'],
                'thursday' => ['09:00-20:00'],
            ],

            [
                'sunday' => ['08:00-14:00'],
                'tuesday' => ['08:00-14:00'],
                'thursday' => ['08:00-14:00'],
                'saturday' => $this->faker->boolean(50) ? ['09:00-15:00'] : [],
            ],

            [
                'sunday' => ['18:00-23:00'],
                'monday' => ['18:00-23:00'],
                'tuesday' => ['18:00-23:00'],
                'wednesday' => ['18:00-23:00'],
                'thursday' => ['18:00-23:00'],
            ],

            $this->generateCompletelyRandomSchedule(),
        ];

        return json_encode($this->faker->randomElement($scheduleTypes));
    }

    /**
     * Generate a completely random working schedule
     *
     * @return array
     */
    private function generateCompletelyRandomSchedule(): array
    {
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $schedule = [];

        $workingDays = $this->faker->randomElements(
            $days,
            $this->faker->numberBetween(3, 5)
        );

        foreach ($workingDays as $day) {
            $hasSplitShift = $this->faker->boolean(30);

            if ($hasSplitShift) {
                $morningStart = $this->faker->numberBetween(7, 11) . ':00';
                $morningEnd = $this->faker->numberBetween(12, 15) . ':00';

                $eveningStart = $this->faker->numberBetween(16, 19) . ':00';
                $eveningEnd = $this->faker->numberBetween(20, 22) . ':00';

                $schedule[$day] = ["$morningStart-$morningEnd", "$eveningStart-$eveningEnd"];
            } else {
                $startHour = $this->faker->numberBetween(7, 16);
                $duration = $this->faker->numberBetween(3, 8);
                $endHour = min($startHour + $duration, 23);

                $schedule[$day] = ["{$startHour}:00-{$endHour}:00"];
            }
        }

        return $schedule;
    }
}
