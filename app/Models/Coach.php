<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasEncodedId;

class Coach extends Authenticatable
{
    use HasFactory , SoftDeletes , HasEncodedId;

    protected $table = 'coaches';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'bio',
        'password',
        'club_id',
        'gender',
        'profile_image',
        'experience_years',
        'certifications',
        'specializations',
        'employment_type',
        'working_hours',
        'verification_code',
        'email_verified_at',
    ];

    protected $casts = [
        'certifications' => 'array',
        'specializations' => 'array',
        'working_hours' => 'array',
        'email_verified_at' => 'datetime',
    ];

    public function workoutPlans()
    {
        return $this->hasMany(WorkoutPlan::class);
    }
    
    public function club()
    {
        return $this->belongsTo(Club::class);
    }
    
    /**
     * Override the getEncodedId method for Coach model
     * to ensure consistent encoding/decoding with the restoreCoach method
     */
    public function getEncodedId()
    {
        $encodedId = base64_encode('coach-' . $this->id . '-' . time());
        
        $encodedId = str_replace(['+', '/', '='], ['-', '_', ''], $encodedId);
        
        return $encodedId;
    }
    
    /**
     * Accessor for encoded_id
     */
    public function getEncodedIdAttribute()
    {
        return $this->getEncodedId();
    }

    /**
     * Override to handle the specific coach ID format
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field && $field !== 'encoded_id') {
            return parent::resolveRouteBinding($value, $field);
        }
        
        try {
            $value = str_replace(['-', '_'], ['+', '/'], $value);
            $paddingLength = strlen($value) % 4;
            if ($paddingLength) {
                $value .= str_repeat('=', 4 - $paddingLength);
            }
            
            $decoded = base64_decode($value);
            
            if (preg_match('/^coach-(\d+)-\d+$/', $decoded, $matches)) {
                $id = $matches[1];
                return $this->where('id', $id)->first();
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return null;
    }
    
    /**
     * Static method to find a coach by encoded ID
     */
    public static function fromEncodedId($encoded_id)
    {
        $instance = new static;
        return $instance->resolveRouteBinding($encoded_id);
    }

    public function dietPlans()
    {
        return $this->hasMany(DietPlan::class);
    }

    public function courses()
    {
        return $this->hasMany(courses::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Get working hours as an array, handling various formats
     */
    public function getWorkingHoursArray()
    {
        $defaultHours = [
            'Monday' => '9:00 AM - 5:00 PM',
            'Tuesday' => '9:00 AM - 5:00 PM',
            'Wednesday' => '9:00 AM - 5:00 PM', 
            'Thursday' => '9:00 AM - 5:00 PM',
            'Friday' => '9:00 AM - 5:00 PM',
            'Saturday' => '10:00 AM - 3:00 PM',
            'Sunday' => 'Closed'
        ];
        
        if (empty($this->working_hours)) {
            return $defaultHours;
        }
        
        if (is_array($this->working_hours)) {
            return array_merge($defaultHours, $this->working_hours);
        }
        
        if (is_string($this->working_hours)) {
            try {
                $decoded = json_decode($this->working_hours, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return array_merge($defaultHours, $decoded);
                }
            } catch (\Exception $e) {
            }
        }
        
        return $defaultHours;
    }
}
