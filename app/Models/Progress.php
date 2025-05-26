<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncodedId;

class Progress extends Model
{
    use HasFactory, HasEncodedId;

    protected $table = 'progress';

    protected $fillable = [
        'user_id',
        'weight',
        'body_fat',
        'notes',        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
