<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Modules\Authentication\Models\User;

class LoginTest extends TestCase
{
    public function test_user_can_login(): void
    {
        $user = User::factory()->verified()->create([
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
    'email' => $user->email,
    'password' => 'password123',
]);

$response->assertStatus(200)
    ->assertJson(['success' => true])
    ->assertJsonStructure([
        'data' => [
            'user',
            'token',
        ],
    ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_unverified_user_cannot_login(): void
    {
        $user = User::factory()->unverified()->create([
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
            ->assertJson(['email_verified' => false]);
    }
}