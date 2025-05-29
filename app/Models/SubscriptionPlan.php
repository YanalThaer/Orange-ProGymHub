<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasEncodedId;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes, HasEncodedId;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'type',
        'is_active',
        'club_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
