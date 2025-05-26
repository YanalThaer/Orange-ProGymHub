<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Support\Facades\Mail;

class ClubPasswordNotificationController extends Controller
{
    /**
     * Send notification emails after a successful password reset
     *
     * @param Club $club The club that has reset their password
     * @return void
     */
    public static function sendPasswordResetNotification(Club $club)
    {
        Mail::send('emails.club-password-reset-confirmation', ['club' => $club], function ($message) use ($club) {
            $message->to($club->email)
                ->subject('Password Reset Successful - ProGymHub');
        });

        if ($club->admin) {
            Mail::send('emails.admin-club-password-reset', ['club' => $club, 'admin' => $club->admin], function ($message) use ($club) {
                $message->to($club->admin->email)
                    ->subject('Club Password Reset - ProGymHub: ' . $club->name);
            });
        }

        \Illuminate\Support\Facades\Log::info("Password reset notification emails sent for club: {$club->name} ({$club->email})");
    }
}
