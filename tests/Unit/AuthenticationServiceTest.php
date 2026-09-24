<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Str;

class AuthenticationServiceTest extends TestCase
{
    protected AuthenticationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(AuthenticationService::class);
    }

    public function test_can_register_user(): void
    {
        $user = $this->service->register([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'SecurePassword123!',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        $this->assertNotNull($user->email_verification_token);
    }

    public function test_can_verify_email(): void
    {
        $user = User::factory()->unverified()->create([
            'email_verification_token' => Str::random(60),
            'email_verification_expires_at' => now()->addDay(),
        ]);

        $this->service->verifyEmail($user);

        $this->assertNotNull(
            $user->refresh()->email_verified_at
        );

        $this->assertNull(
            $user->email_verification_token
        );

        $this->assertNull(
            $user->email_verification_expires_at
        );
    }

    public function test_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->verified()->create([
            'password' => 'password123',
        ]);

        $this->expectException(InvalidCredentialsException::class);

        $this->service->login(
            $user->email,
            'wrongpassword'
        );
    }
}