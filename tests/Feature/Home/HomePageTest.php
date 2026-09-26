<?php

declare(strict_types=1);

namespace Tests\Feature\Home;

use App\Modules\Article\Enums\ArticleStatus;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Models\Category;
use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('public.index');
    }

    public function test_home_page_contains_published_articles(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create([
            'author_id' => $user->id,
            'title' => 'Home Page Test Article',
            'status' => ArticleStatus::PUBLISHED,
            'published_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('latestArticles', function ($articles) use ($article): bool {
            return $articles->contains('id', $article->id);
        });
    }

    public function test_home_page_does_not_include_unpublished_articles(): void
    {
        $user = User::factory()->create();

        $draft = Article::factory()->draft()->create([
            'author_id' => $user->id,
            'title' => 'Draft Home Article',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('latestArticles', function ($articles) use ($draft): bool {
            return ! $articles->contains('id', $draft->id);
        });
    }

    public function test_home_page_provides_categories(): void
    {
        Category::factory()->create([
            'name' => 'National',
            'slug' => 'national',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('categories');
    }
}