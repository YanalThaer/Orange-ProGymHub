<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scan_time',
        'location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
