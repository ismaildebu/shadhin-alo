<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Modules\Article\Enums\ArticleStatus;
use App\Modules\Article\Models\Article;
use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

   public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->get('/dashboard');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('dashboard.index');
    }

    public function test_dashboard_contains_article_statistics(): void
    {
        $user = User::factory()->create();

        Article::factory()->create([
            'author_id' => $user->id,
            'status' => ArticleStatus::PUBLISHED,
            'published_at' => now(),
        ]);

        Article::factory()->draft()->create([
            'author_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('statistics', function (array $statistics): bool {
            return $statistics['total_articles'] === 2
                && $statistics['published_articles'] === 1
                && $statistics['draft_articles'] === 1;
        });
    }
}