<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasEncodedId;

class WorkoutPlan extends Model
{
    use HasFactory, HasEncodedId, SoftDeletes;

    protected $table = 'workout_plans';

    protected $fillable = [
        'club_id',
        'user_id',
        'coach_id',
        'title',
        'description',
        'category',
        'duration_weeks',
        'difficulty_level',
        'is_template',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function progress()
    {
        return $this->hasMany(WorkoutProgress::class);
    }

    /**
     * Get the users assigned to this workout plan.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workout_plan')->withTimestamps();
    }
}
