<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'coach_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'max_participants',
        'participants_count',
        'price',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'session_registrations');
    }

    public function registrations()
    {
        return $this->hasMany(SessionRegistration::class);
    }
}