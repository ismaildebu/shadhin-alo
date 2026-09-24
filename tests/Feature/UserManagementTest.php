<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Modules\Authentication\Models\User;
use App\Modules\UserManagement\Models\UserProfile;
use App\Modules\UserManagement\Models\UserPreference;
use App\Modules\UserManagement\Models\UserNotificationPreference;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserManagementTest extends TestCase
{
    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/profile');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/profile', [
                'first_name' => 'Updated',
                'last_name' => 'User',
                'bio' => 'Updated profile bio',
                'gender' => 'male',
                'city' => 'Dhaka',
                'is_public_profile' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Profile updated successfully',
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'first_name' => 'Updated',
            'last_name' => 'User',
            'is_public_profile' => true,
        ]);
    }

    public function test_invalid_profile_data_is_rejected(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/profile', [
                'gender' => 'invalid-gender',
                'website' => 'not-a-url',
            ]);

        $response->assertStatus(422);
    }

    public function test_private_profile_is_not_publicly_accessible(): void
    {
        $user = User::factory()->verified()->create();

        UserProfile::create([
            'user_id' => $user->id,
            'is_public_profile' => false,
        ]);

        $response = $this->getJson("/profile/public/{$user->id}");

        $response->assertStatus(404);
    }

    public function test_public_profile_can_be_viewed(): void
    {
        $user = User::factory()->verified()->create();

        UserProfile::create([
            'user_id' => $user->id,
            'is_public_profile' => true,
        ]);

        $response = $this->getJson("/profile/public/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);
    }

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->post('/profile/avatar', [
                'avatar_url' => UploadedFile::fake()->image('avatar.jpg'),
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Avatar updated successfully',
            ]);

        $profile = UserProfile::where('user_id', $user->id)->first();

        $this->assertNotNull($profile);
        $this->assertNotNull($profile->avatar_url);

        $storedPath = str_replace(url('storage/') . '/', '', $profile->avatar_url);

        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_user_can_upload_cover_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->post('/profile/cover-image', [
                'cover_image_url' => UploadedFile::fake()->image('cover.jpg'),
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Cover image updated successfully',
            ]);

        $profile = UserProfile::where('user_id', $user->id)->first();

        $this->assertNotNull($profile);
        $this->assertNotNull($profile->cover_image_url);

        $storedPath = str_replace(url('storage/') . '/', '', $profile->cover_image_url);

        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_user_can_update_preferences(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/preferences', [
                'theme' => 'dark',
                'language' => 'bn-BD',
                'timezone' => 'Asia/Dhaka',
                'items_per_page' => 25,
                'privacy_level' => 'private',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Preferences updated successfully',
            ]);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'theme' => 'dark',
            'language' => 'bn-BD',
            'timezone' => 'Asia/Dhaka',
            'items_per_page' => 25,
        ]);
    }

    public function test_invalid_preferences_are_rejected(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/preferences', [
                'theme' => 'invalid-theme',
                'items_per_page' => 500,
                'timezone' => 'invalid-timezone',
            ]);

        $response->assertStatus(422);
    }

    public function test_user_can_update_theme_and_language(): void
    {
        $user = User::factory()->verified()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson('/preferences/theme', [
                'theme' => 'dark',
            ])
            ->assertStatus(200);

        $this->actingAs($user, 'sanctum')
            ->putJson('/preferences/language', [
                'language' => 'bn-BD',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'theme' => 'dark',
            'language' => 'bn-BD',
        ]);
    }

    public function test_user_can_update_notification_preference(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/preferences/notifications/email', [
                'email_enabled' => true,
                'push_enabled' => false,
                'sms_enabled' => false,
                'frequency' => 'daily',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Notification preference updated successfully',
            ]);

        $this->assertDatabaseHas('user_notification_preferences', [
            'user_id' => $user->id,
            'notification_type' => 'email',
            'email_enabled' => true,
            'push_enabled' => false,
            'sms_enabled' => false,
            'frequency' => 'daily',
        ]);
    }

    public function test_user_can_disable_notification_preference(): void
    {
        $user = User::factory()->verified()->create();

        UserNotificationPreference::create([
            'user_id' => $user->id,
            'notification_type' => 'email',
            'email_enabled' => true,
            'push_enabled' => true,
            'in_app_enabled' => true,
            'sms_enabled' => true,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/preferences/notifications/email/disable');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Notifications disabled successfully',
            ]);

        $this->assertDatabaseHas('user_notification_preferences', [
            'user_id' => $user->id,
            'notification_type' => 'email',
            'email_enabled' => false,
            'push_enabled' => false,
            'in_app_enabled' => false,
            'sms_enabled' => false,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_user_management(): void
    {
        $this->getJson('/profile')->assertStatus(401);
        $this->getJson('/preferences')->assertStatus(401);
        $this->putJson('/profile', ['first_name' => 'Test'])->assertStatus(401);
        $this->putJson('/preferences', ['theme' => 'dark'])->assertStatus(401);
    }
}
