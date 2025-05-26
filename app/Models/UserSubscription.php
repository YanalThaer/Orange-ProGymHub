<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id',
        'club_id',
        'plan_id',
        'start_date',
        'end_date',
        'payment_status',
        'payment_method',
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
    
    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
