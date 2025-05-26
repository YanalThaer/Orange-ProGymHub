<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRegistration extends Model
{
    use HasFactory;

    protected $table = 'session_registrations';

    protected $fillable = [
        'user_id',
        'session_id',
        'registration_time',
        'payment_status',
        'payment_method',
    ];

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
