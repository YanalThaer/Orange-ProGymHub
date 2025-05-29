<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    use HasFactory;

    protected $table = 'diet_plans';

    protected $fillable = [
        'user_id',
        'coach_id',
        'title',
        'description',
        'meal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function progress()
    {
        return $this->hasMany(DietProgress::class);
    }
}
