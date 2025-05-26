<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Club;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;    /**
     * Test that admin can log in successfully.
     */
    public function test_admin_login_successful()
    {
        // Create admin and a club
        $admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);
        
        $club = Club::factory()->create();
        
        // Attempt to login as admin
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password'
        ]);
        
        // Check redirection to admin dashboard
        $response->assertRedirect(route('admin.dashboard'));
        
        // Verify admin is authenticated
        $this->assertAuthenticatedAs($admin, 'admin');
    }
    
    /**
     * Test that notifications are created when a club profile is updated by an admin.
     */
    public function test_club_update_by_admin_creates_notification()
    {
        // Create admin and club
        $admin = Admin::factory()->create();
        $club = Club::factory()->create();
        
        // Login as admin
        $this->actingAs($admin, 'admin');
        
        // Update club profile
        $response = $this->put(route('clubs.update', $club->encoded_id), [
            'name' => 'Updated Club Name',
            'email' => $club->email,
            // Add other required fields...
        ]);
        
        // Verify a notification was created for the club
        $this->assertDatabaseHas('notifications', [
            'type' => 'club_update',
            'notifiable_type' => 'App\\Models\\Club',
            'notifiable_id' => $club->id
        ]);
    }
    
    /**
     * Test that notifications are created when a club updates its own profile.
     */
    public function test_club_self_update_creates_admin_notification()
    {
        // Create admin and club
        $admin = Admin::factory()->create();
        $club = Club::factory()->create([
            'admin_id' => $admin->id
        ]);
        
        // Login as club
        $this->actingAs($club, 'club');
        
        // Update club profile
        $response = $this->put(route('myclub.update', $club->encoded_id), [
            'name' => 'Self Updated Club',
            'email' => $club->email,
            // Add other required fields...
        ]);
        
        // Verify a notification was created for the admin
        $this->assertDatabaseHas('notifications', [
            'type' => 'admin_notification',
            'notifiable_type' => 'App\\Models\\Admin',
            'notifiable_id' => $admin->id
        ]);
    }
    
    /**
     * Test that notifications can be marked as read.
     */
    public function test_notifications_can_be_marked_as_read()
    {
        // Create club and notification
        $club = Club::factory()->create();
        
        $notification = Notification::create([
            'type' => 'test_notification',
            'title' => 'Test Notification',
            'message' => 'This is a test notification',
            'data' => ['test' => 'data'],
            'notifiable_type' => 'App\\Models\\Club',
            'notifiable_id' => $club->id
        ]);
        
        // Login as club
        $this->actingAs($club, 'club');
        
        // Mark notification as read
        $response = $this->get(route('notifications.read', $notification->id));
          // Verify notification is marked as read
        $this->assertNotNull($notification->fresh()->read_at);
    }
    
    /**
     * Test that only unread notifications are shown in the notification dropdown.
     */
    public function test_only_unread_notifications_appear_in_dropdown()
    {
        // Create club and notifications (read and unread)
        $club = Club::factory()->create();
        
        // Create unread notification
        $unreadNotification = Notification::create([
            'type' => 'unread_notification',
            'title' => 'Unread Notification',
            'message' => 'This notification is unread',
            'data' => ['test' => 'unread'],
            'notifiable_type' => 'App\\Models\\Club',
            'notifiable_id' => $club->id
        ]);
        
        // Create read notification
        $readNotification = Notification::create([
            'type' => 'read_notification',
            'title' => 'Read Notification',
            'message' => 'This notification is read',
            'data' => ['test' => 'read'],
            'notifiable_type' => 'App\\Models\\Club',
            'notifiable_id' => $club->id,
            'read_at' => now()
        ]);
        
        // Login as club
        $this->actingAs($club, 'club');
        
        // Get dropdown content
        $response = $this->getJson(route('notifications.dropdownContent'));
        
        // Assert the response is successful
        $response->assertStatus(200);
        
        // Check that the unread notification is in the response
        $response->assertJsonPath('html', fn($html) => 
            strpos($html, 'Unread Notification') !== false
        );
        
        // Check that the read notification is not in the response
        $response->assertJsonPath('html', fn($html) => 
            strpos($html, 'Read Notification') === false
        );
    }    /**
     * Test that Ajax request to mark a notification as read works.
     */
    public function test_ajax_mark_notification_as_read()
    {
        // Create club and notification
        $club = Club::factory()->create();
        
        $notification = Notification::create([
            'type' => 'ajax_notification',
            'title' => 'Ajax Notification',
            'message' => 'This notification will be marked as read via Ajax',
            'data' => ['test' => 'ajax'],
            'notifiable_type' => 'App\\Models\\Club',
            'notifiable_id' => $club->id
        ]);
        
        // Login as club
        $this->actingAs($club, 'club');
        
        // Make Ajax request to mark notification as read
        $response = $this->getJson(route('notifications.read', $notification->id));
        
        // Assert the response is successful
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Verify notification is marked as read
        $this->assertNotNull($notification->fresh()->read_at);
    }
      
    /**
     * Test that admin and club update notifications still work properly.
     * This is a replacement for the admin login notification test that was removed
     * since that functionality is no longer in place.
     */
    public function test_club_update_notifications()
    {
        // Create admin and club
        $admin = Admin::factory()->create();
        $club = Club::factory()->create();
        
        // Create a club update notification
        $notification = Notification::create([
            'type' => 'club_update',
            'title' => 'Club Profile Updated',
            'message' => "Your club profile has been updated by an administrator.",
            'data' => [
                'admin_id' => $admin->id,
                'update_at' => now()->toDateTimeString()
            ],
            'notifiable_type' => 'App\\Models\\Club',
            'notifiable_id' => $club->id
        ]);
        
        // Login as club
        $this->actingAs($club, 'club');
        
        // When club clicks on notification, they should be redirected to their profile
        $response = $this->get(route('notifications.read', $notification->id));
        
        // Verify redirection to club profile
        $response->assertRedirect(route('club.profile'));
    }
}
