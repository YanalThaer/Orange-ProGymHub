<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'profile_picture',
        'role',
        'last_login_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get all of the admin's custom notifications.
     */
    public function customNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Get unread notifications for the admin.
     */
    public function unreadNotifications()
    {
        return $this->customNotifications()->whereNull('read_at');
    }

    /**
     * Get all notifications for this admin.
     */
    public function allNotifications()
    {
        return $this->customNotifications()->latest();
    }
}
