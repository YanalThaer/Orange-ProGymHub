<?php
namespace App\Models;

use App\Traits\HasEncodedId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

class Club extends Authenticatable implements CanResetPassword
{
    use HasFactory, SoftDeletes, HasEncodedId, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'bio',
        'location',
        'address',
        'city',
        'country',
        'description',
        'capacity',
        'has_parking',
        'has_wifi',
        'has_showers',
        'has_lockers',
        'has_pool',
        'has_sauna',
        'website',
        'social_media',
        'emergency_contact',
        'status',
        'established_date',
        'admin_id',
        'logo',
        'open_time',
        'close_time',
        'working_days',
        'special_hours',
        'verification_code',
        'verification_code_expires_at',
        'email_verified',
        'email_verified_at',
    ];

    protected $casts = [
        'social_media' => 'array',
        'working_days' => 'array',
        'special_hours' => 'array',
        'has_parking' => 'boolean',
        'has_wifi' => 'boolean',
        'has_showers' => 'boolean',
        'has_lockers' => 'boolean',
        'has_pool' => 'boolean',
        'has_sauna' => 'boolean',
        'established_date' => 'date',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'verification_code_expires_at' => 'datetime',
        'email_verified' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get all of the club's custom notifications.
     */
    public function customNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Get unread notifications for the club.
     */
    public function unreadNotifications()
    {
        return $this->customNotifications()->whereNull('read_at');
    }

    /**
     * Get all notifications for this club.
     */
    public function allNotifications()
    {
        return $this->customNotifications()->latest();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function subscriptionPlans()
    {
        return $this->hasMany(SubscriptionPlan::class);
    }
    
    public function workoutPlans()
    {
        return $this->hasMany(WorkoutPlan::class);
    }
    
    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function courses()
    {
        return $this->hasMany(courses::class);
    }

    /**
     * Get the encoded ID attribute
     * 
     * @return string
     */
    public function getEncodedIdAttribute()
    {
        return $this->getEncodedId();
    }

    /**
     * Verificar si el club tiene una suscripción activa
     */
    public function hasActiveSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();
    }

    /**
     * Obtener la suscripción activa del club
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }

    public function isOpen()
    {
        $currentDay = now()->dayOfWeek;
        
        $currentTime = now()->format('H:i');
        
        if (!$this->working_days) {
            return false;
        }
        
        $workingDays = is_array($this->working_days) ? $this->working_days : json_decode($this->working_days, true);
        
        if (!isset($workingDays[$currentDay]) || !$workingDays[$currentDay]) {
            return false;
        }
        
        if ($this->special_hours) {
            $specialHours = is_array($this->special_hours) ? $this->special_hours : json_decode($this->special_hours, true);
            $today = now()->format('Y-m-d');
            
            if (isset($specialHours[$today])) {
                $hours = $specialHours[$today];
                return $currentTime >= $hours['open'] && $currentTime <= $hours['close'];
            }
        }
        
        $openTime = $this->open_time->format('H:i');
        $closeTime = $this->close_time->format('H:i');
        
        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }
    
    /**
     * Get the email address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = url(route('club.password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        \Illuminate\Support\Facades\Mail::send('emails.club-password-reset', ['resetUrl' => $url, 'club' => $this], function ($message) {
            $message->to($this->email)
                ->subject('Reset Password Notification - ProGymHub');
        });
    }
}
