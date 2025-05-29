<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClubPasswordResetHandlerController extends Controller
{
    /**
     * Handle the custom password reset link generation for clubs
     * To be used directly from other controllers
     */
    public static function sendPasswordResetLink($club)
    {
        try {
            $status = Password::broker('clubs')->sendResetLink(
                ['email' => $club->email]
            );

            $success = $status === Password::RESET_LINK_SENT;

            if ($success) {
                \Illuminate\Support\Facades\Log::info("Password reset link sent to club: {$club->email}");
            } else {
                \Illuminate\Support\Facades\Log::warning("Failed to send password reset link to club: {$club->email}. Status: {$status}");
            }

            return $success;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send password reset link to club: {$club->email}. Error: " . $e->getMessage());
            return false;
        }
    }
}
