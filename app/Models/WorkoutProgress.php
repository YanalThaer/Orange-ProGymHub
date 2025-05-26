<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutProgress extends Model
{
    use HasFactory;

    protected $table = 'workout_progress';

    protected $fillable = [
        'user_id',
        'workout_id',
        'status',
    ];

    public function workout()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
