<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ClubVerificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function admin_can_create_club_and_send_verification_email()
    {
        // Create admin user
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        // Club data
        $clubData = [
            'name' => $this->faker->company,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'description' => $this->faker->paragraph,
            'open_time' => '08:00',
            'close_time' => '22:00',
        ];

        // Submit the club creation form
        $response = $this->post(route('clubs.store'), $clubData);

        // Assert redirect to verification form
        $response->assertRedirect(route('admin.club.verify.email.form'));
        
        // Assert session contains club data and verification code
        $this->assertTrue(session()->has('club_data'));
        $this->assertTrue(session()->has('verification_code'));
        $this->assertTrue(session()->has('verification_expires_at'));
        
        // Assert club is not yet created in database
        $this->assertEquals(0, Club::count());
    }

    /** @test */
    public function admin_can_verify_club_email_with_correct_code()
    {
        // Create admin user
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        
        // Mock session data
        $verificationCode = '123456';
        $clubData = [
            'name' => $this->faker->company,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'password' => Hash::make('password123'),
            'description' => $this->faker->paragraph,
            'open_time' => '08:00',
            'close_time' => '22:00',
        ];
        
        Session::put('club_data', $clubData);
        Session::put('verification_code', $verificationCode);
        Session::put('verification_expires_at', now()->addMinutes(30));
        
        // Submit correct verification code
        $response = $this->post(route('admin.club.verify.email'), [
            'verification_code' => $verificationCode
        ]);
        
        // Assert club is created in database
        $this->assertEquals(1, Club::count());
        $club = Club::first();
        
        // Assert club properties
        $this->assertEquals($clubData['name'], $club->name);
        $this->assertEquals($clubData['email'], $club->email);
        $this->assertTrue($club->email_verified);
        $this->assertNotNull($club->email_verified_at);
        
        // Assert session data is cleared
        $this->assertFalse(session()->has('club_data'));
        $this->assertFalse(session()->has('verification_code'));
        $this->assertFalse(session()->has('verification_expires_at'));
        
        // Assert redirect to club page
        $response->assertRedirect(route('clubs.show', $club->encoded_id));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function admin_cannot_verify_club_email_with_incorrect_code()
    {
        // Create admin user
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        
        // Mock session data
        $verificationCode = '123456';
        $clubData = [
            'name' => $this->faker->company,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'password' => Hash::make('password123'),
            'description' => $this->faker->paragraph,
            'open_time' => '08:00',
            'close_time' => '22:00',
        ];
        
        Session::put('club_data', $clubData);
        Session::put('verification_code', $verificationCode);
        Session::put('verification_expires_at', now()->addMinutes(30));
        
        // Submit incorrect verification code
        $response = $this->post(route('admin.club.verify.email'), [
            'verification_code' => '654321'
        ]);
        
        // Assert club is not created in database
        $this->assertEquals(0, Club::count());
        
        // Assert session data is still present
        $this->assertTrue(session()->has('club_data'));
        $this->assertTrue(session()->has('verification_code'));
        $this->assertTrue(session()->has('verification_expires_at'));
        
        // Assert error message
        $response->assertSessionHasErrors('verification_code');
    }

    /** @test */
    public function admin_cannot_verify_club_email_with_expired_code()
    {
        // Create admin user
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        
        // Mock session data with expired code
        $verificationCode = '123456';
        $clubData = [
            'name' => $this->faker->company,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'password' => Hash::make('password123'),
            'description' => $this->faker->paragraph,
            'open_time' => '08:00',
            'close_time' => '22:00',
        ];
        
        Session::put('club_data', $clubData);
        Session::put('verification_code', $verificationCode);
        Session::put('verification_expires_at', now()->subMinutes(5)); // Expired 5 minutes ago
        
        // Submit correct verification code
        $response = $this->post(route('admin.club.verify.email'), [
            'verification_code' => $verificationCode
        ]);
        
        // Assert club is not created in database
        $this->assertEquals(0, Club::count());
        
        // Assert session data is cleared
        $this->assertFalse(session()->has('club_data'));
        $this->assertFalse(session()->has('verification_code'));
        $this->assertFalse(session()->has('verification_expires_at'));
        
        // Assert redirect to club creation page with error
        $response->assertRedirect(route('clubs.create'));
        $response->assertSessionHasErrors('verification_code');
    }

    /** @test */
    public function admin_can_resend_verification_code()
    {
        // Create admin user
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        
        // Mock session data
        $clubData = [
            'name' => $this->faker->company,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'password' => Hash::make('password123'),
            'description' => $this->faker->paragraph,
            'open_time' => '08:00',
            'close_time' => '22:00',
        ];
        
        Session::put('club_data', $clubData);
        Session::put('verification_code', '123456');
        Session::put('verification_expires_at', now()->addMinutes(5));
        
        // Request to resend verification code
        $response = $this->get(route('admin.club.resend.verification.code'));
        
        // Assert session has new verification code
        $this->assertTrue(session()->has('verification_code'));
        $this->assertTrue(session()->has('verification_expires_at'));
        
        // Assert old and new verification codes are different
        $this->assertNotEquals('123456', session('verification_code'));
        
        // Assert expiry time is extended
        $this->assertTrue(now()->addMinutes(29)->lessThan(session('verification_expires_at')));
        
        // Assert redirect back with status message
        $response->assertRedirect();
        $response->assertSessionHas('status');
    }
}
