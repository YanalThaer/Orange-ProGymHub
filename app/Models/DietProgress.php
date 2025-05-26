<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietProgress extends Model
{
    use HasFactory;

    protected $table = 'diet_progress';

    protected $fillable = [
        'user_id',
        'diet_id',
        'status',
    ];

    public function diet()
    {
        return $this->belongsTo(DietPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
