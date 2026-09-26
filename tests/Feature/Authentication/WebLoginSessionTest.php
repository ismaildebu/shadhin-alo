<?php

declare(strict_types=1);

namespace Tests\Feature\Authentication;

use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebLoginSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_login_persists_authenticated_session(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@shadhinalo.com',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@shadhinalo.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboard = $this->get('/dashboard');

        $dashboard->assertOk();
        $this->assertAuthenticatedAs($user);
    }
}
