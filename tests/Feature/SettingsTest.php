<?php

namespace Tests\Feature;

use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_email_settings(): void
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/email-settings');

        $response->assertOk()
            ->assertJsonPath('status', 'success');
    }

    public function test_non_admin_cannot_view_email_settings(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/email-settings');

        $response->assertForbidden();
    }

    public function test_admin_can_update_email_settings(): void
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/email-settings', [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.example.com',
            'mail_port' => 587,
            'mail_username' => 'test@example.com',
            'mail_password' => 'secret',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'test@example.com',
            'mail_from_name' => 'স্বাধীন আলো',
            'is_active' => true,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('email_settings', [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.example.com',
            'mail_port' => 587,
            'mail_from_address' => 'test@example.com',
        ]);
    }

    public function test_non_admin_cannot_update_email_settings(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/email-settings', [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.example.com',
            'mail_port' => 587,
            'mail_from_address' => 'test@example.com',
            'mail_from_name' => 'Test',
        ]);

        $response->assertForbidden();
    }

    public function test_test_email_requires_valid_recipient_email(): void
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/email-settings/test', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}

