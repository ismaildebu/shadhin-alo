<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Modules\Authentication\Notifications\VerifyEmail;

class RegisterTest extends TestCase
{
    public function test_user_can_register(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'gdpr_consent' => true,
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['user', 'token']]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_user_cannot_register_with_existing_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'gdpr_consent' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_password_must_be_strong(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
            'gdpr_consent' => true,
        ]);

        $response->assertStatus(422);
    }
}