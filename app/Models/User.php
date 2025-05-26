<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasEncodedId;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasEncodedId , SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'join_date',
        'goal',
        'phone_number',
        'date_of_birth',
        'gender',
        'height_cm',
        'weight_kg',
        'target_weight_kg',
        'bmi',
        'body_fat_percentage',
        'health_conditions',
        'injuries',
        'allergies',
        'medications',
        'fitness_level',
        'training_days_per_week',
        'preferred_training_time',
        'preferred_workout_duration',
        'exercise_preferences',
        'exercise_dislikes',
        'diet_preference',
        'meals_per_day',
        'food_preferences',
        'food_dislikes',
        'coach_id',
        'club_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'join_date' => 'datetime',
        'date_of_birth' => 'datetime',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function dietPlans()
    {
        return $this->hasMany(DietPlan::class);
    }

    public function workoutPlans()
    {
        return $this->belongsToMany(WorkoutPlan::class, 'user_workout_plan')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'session_registrations');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }
    
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Check if the user has an active subscription
     * 
     * @return bool
     */
    public function hasActiveSubscription()
    {
        return $this->subscriptions()
            ->where('end_date', '>=', now())
            ->exists();
    }

    /**
     * Get the active subscription of the user
     * 
     * @return UserSubscription|null
     */
    public function getActiveSubscription()
    {
        return $this->subscriptions()
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }

    /**
     * Check if the user can create a new subscription
     * 
     * @param int|null $clubId The club ID for which to check subscription eligibility
     * @return array [bool $canSubscribe, string $message]
     */
    public function canSubscribe($clubId = null)
    {
        $activeSubscription = $this->getActiveSubscription();
        
        if (!$activeSubscription) {
            return [true, ''];
        }
        
        if ($clubId && $activeSubscription->club_id == $clubId) {
            return [false, 'You already have an active subscription at this club.'];
        }
        
        $daysRemaining = now()->diffInDays($activeSubscription->end_date, false);
        
        if ($daysRemaining <= 3 && $daysRemaining >= 0) {
            return [true, ''];
        }
        
        return [false, 'You already have an active subscription. You can subscribe to a new club only in the last 3 days of your current subscription.'];
    }

    /**
     * Calculate the start date for a new subscription
     * 
     * @return Carbon
     */
    public function getNewSubscriptionStartDate()
    {
        $activeSubscription = $this->getActiveSubscription();
        
        if (!$activeSubscription) {
            return now();
        }
        
        return $activeSubscription->end_date->copy()->addDay();
    }
    
    /**
     * Get the currently active subscription for the user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userSubscription()
    {
        return $this->hasOne(UserSubscription::class)
                    ->where('end_date', '>=', now())
                    ->orderBy('created_at', 'desc');
    }
}
