<?php

namespace Tests\Feature;

use App\Models\Club;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ClubPasswordResetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function password_reset_link_is_sent_after_club_verification()
    {
        // Create a verified club
        $club = Club::factory()->create([
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);
        
        // Directly call the method for sending password reset link
        $success = \App\Http\Controllers\Auth\ClubPasswordResetHandlerController::sendPasswordResetLink($club);
        
        // Assert the reset link was sent successfully
        $this->assertTrue($success);
        
        // Check that an email was sent to the club
        Mail::assertSent(function ($mail) use ($club) {
            return $mail->hasTo($club->email) && 
                   $mail->subject === 'Reset Password Notification - ProGymHub';
        });
    }

    /** @test */
    public function club_can_request_password_reset_link()
    {
        // Create a verified club
        $club = Club::factory()->create([
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);
        
        // Request password reset link
        $response = $this->post(route('club.password.email'), [
            'email' => $club->email,
        ]);
        
        // Assert success
        $response->assertSessionHas('status');
        
        // Check that an email was sent to the club
        Mail::assertSent(function ($mail) use ($club) {
            return $mail->hasTo($club->email) && 
                   $mail->subject === 'Reset Password Notification - ProGymHub';
        });
    }

    /** @test */
    public function club_can_reset_password_with_valid_token()
    {
        // Create a verified club
        $club = Club::factory()->create([
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);
        
        // Create a password reset token
        $token = Password::broker('clubs')->createToken($club);
        
        // Reset the password using the token
        $response = $this->post(route('club.password.update'), [
            'token' => $token,
            'email' => $club->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        // Assert redirect to login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
        
        // Verify club can log in with new password
        $this->assertTrue(auth()->guard('club')->attempt([
            'email' => $club->email,
            'password' => 'newpassword123',
        ]));
    }

    /** @test */
    public function club_reset_password_fails_with_invalid_token()
    {
        // Create a verified club
        $club = Club::factory()->create([
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);
        
        // Try to reset the password with an invalid token
        $response = $this->post(route('club.password.update'), [
            'token' => 'invalid-token',
            'email' => $club->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        // Assert error
        $response->assertSessionHasErrors('email');
        
        // Verify club cannot log in with new password
        $this->assertFalse(auth()->guard('club')->attempt([
            'email' => $club->email,
            'password' => 'newpassword123',
        ]));
    }

    /** @test */
    public function club_cannot_request_password_reset_with_unverified_email()
    {
        // Create an unverified club
        $club = Club::factory()->create([
            'email_verified' => false,
            'email_verified_at' => null,
        ]);
        
        // Request password reset link
        $response = $this->post(route('club.password.email'), [
            'email' => $club->email,
        ]);
        
        // Assert no email was sent
        Mail::assertNothingSent();
        
        // Assert error message
        $response->assertSessionHasErrors('email');
    }
}
