<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachPayment extends Model
{
    use HasFactory;

    protected $table = 'coach_payments';

    protected $fillable = [
        'user_id',
        'coach_id',
        'amount',
        'payment_date',
        'payment_method',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
