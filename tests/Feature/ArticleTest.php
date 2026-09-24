<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Modules\Article\Models\Article;
use App\Modules\Article\Models\Category;
use App\Modules\Article\Models\Subcategory;
use App\Modules\Article\Models\Tag;
use App\Modules\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_tables_exist(): void
    {
        $this->assertTrue(\Schema::hasTable('articles'));
        $this->assertTrue(\Schema::hasTable('categories'));
        $this->assertTrue(\Schema::hasTable('tags'));
        $this->assertTrue(\Schema::hasTable('article_categories'));
        $this->assertTrue(\Schema::hasTable('article_tags'));
    }

    public function test_article_factory_creates_article(): void
    {
        $article = Article::factory()->create();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
        ]);
    }

    public function test_article_category_and_tag_relationships_work(): void
    {
        $article = Article::factory()->create();
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $article->categories()->attach($category);
        $article->tags()->attach($tag);

        $this->assertTrue(
            $article->categories()->whereKey($category->id)->exists()
        );

        $this->assertTrue(
            $article->tags()->whereKey($tag->id)->exists()
        );
    }

    public function test_articles_route_is_registered(): void
    {
        $this->assertTrue(Route::has('articles.index') || collect(Route::getRoutes())
            ->contains(fn ($route) =>
                $route->uri() === 'articles' &&
                in_array('GET', $route->methods(), true)
            ));
    }

    public function test_public_articles_endpoint_is_accessible(): void
    {
        Article::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->getJson('/articles');

        $response->assertSuccessful();
    }



public function test_draft_article_is_not_returned_by_public_articles_endpoint(): void
{
    $draft = Article::factory()->create([
        'status' => 'draft',
    ]);

    $response = $this->getJson('/articles');

    $response->assertSuccessful();

    $ids = collect($response->json('data'))->pluck('id');

    $this->assertFalse($ids->contains($draft->id));
}

public function test_published_article_is_returned_by_public_articles_endpoint(): void
{
    $published = Article::factory()->create([
        'status' => 'published',
    ]);

    $response = $this->getJson('/articles');

    $response->assertSuccessful();

    $ids = collect($response->json('data'))->pluck('id');

    $this->assertTrue($ids->contains($published->id));
}


    public function test_article_creation_requires_authentication(): void
    {
        $response = $this->postJson('/articles', [
            'title' => 'Test Article',
            'content' => 'This is test article content with enough content.',
        ]);

        $response->assertUnauthorized();
    }

    public function test_article_policy_is_registered(): void
    {
        $policy = Gate::getPolicyFor(Article::class);

        $this->assertNotNull($policy);
        $this->assertSame(
            \App\Modules\Article\Policies\ArticlePolicy::class,
            $policy::class
        );
    }

    public function test_article_model_supports_soft_deletes(): void
    {
        $article = Article::factory()->create();

        $article->delete();

        $this->assertSoftDeleted('articles', [
            'id' => $article->id,
        ]);
    }


   public function test_due_scheduled_article_is_published(): void
    {
        $article = Article::factory()->create([
            'status' => 'draft',
            'published_at' => null,
            'scheduled_publish_at' => now()->subMinute(),
        ]);

        $this->artisan('articles:publish-scheduled')
            ->assertSuccessful();

        $article->refresh();

        $this->assertSame('published', $article->status->value);
        $this->assertNotNull($article->published_at);
        $this->assertNull($article->scheduled_publish_at);
    }

    public function test_future_scheduled_article_is_not_published(): void
    {
        $article = Article::factory()->create([
            'status' => 'draft',
            'published_at' => null,
            'scheduled_publish_at' => now()->addHour(),
        ]);

        $this->artisan('articles:publish-scheduled')
            ->assertSuccessful();

        $article->refresh();

        $this->assertSame('draft', $article->status->value);
        $this->assertNull($article->published_at);
        $this->assertNotNull($article->scheduled_publish_at);
    }

    public function test_authenticated_user_is_assigned_as_article_author(): void
    {
        $user = User::factory()->create();
        $user->assignRole('author');

        $this->actingAs($user);

        $article = Article::factory()->create([
            'author_id' => $user->id,
        ]);

        $article->load('author');

        $this->assertSame($user->id, $article->author->id);
    }

    public function test_authenticated_user_is_assigned_as_article_author_when_creating_article(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('author');

        $this->actingAs($user);

        $response = $this->postJson('/articles', [
            'title' => 'Author Assignment Test Article',
            'slug' => 'author-assignment-test-article',
            'excerpt' => 'This is a valid article excerpt for testing author assignment.',
            'content' => str_repeat('This is valid article content. ', 10),
        ]);

        $response->assertCreated();

        $article = Article::query()
            ->where('slug', 'author-assignment-test-article')
            ->firstOrFail();

        $this->assertSame($user->id, $article->author_id);
        $this->assertSame($user->id, $article->author->id);
        $this->assertSame($user->name, $response->json('data.author.name'));
    }

    public function test_editor_can_update_another_authors_article(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $author = User::factory()->create();
        $author->assignRole('author');

        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $article = Article::factory()->create([
            'author_id' => $author->id,
            'title' => 'Original Article Title',
            'status' => 'published',
        ]);
        $this->assertSame('published', $article->status->value);

        $this->actingAs($editor);

        $response = $this->putJson("/articles/{$article->id}", [
            'title' => 'Updated By Editor',
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated By Editor',
        ]);
    }
    public function test_reporter_can_create_article_but_cannot_publish_or_delete(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $reporter = User::factory()->create();
        $reporter->assignRole('reporter');

        $this->actingAs($reporter);

        $createResponse = $this->postJson('/articles', [
            'title' => 'Reporter Test Article',
            'slug' => 'reporter-test-article',
            'excerpt' => 'This is a valid reporter article excerpt.',
            'content' => str_repeat('This is valid reporter article content. ', 10),
        ]);

        $createResponse->assertCreated();

        $article = Article::query()
            ->where('slug', 'reporter-test-article')
            ->firstOrFail();

        $publishResponse = $this->postJson("/articles/{$article->id}/publish");
        $publishResponse->assertForbidden();

        $deleteResponse = $this->deleteJson("/articles/{$article->id}");
        $deleteResponse->assertForbidden();
    }

    public function test_article_can_be_created_with_categories(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('author');

        $categories = Category::factory()->count(2)->create();

        $this->actingAs($user);

        $response = $this->postJson('/articles', [
            'title' => 'Category Assignment Test Article',
            'slug' => 'category-assignment-test-article',
            'excerpt' => 'This is a valid article excerpt for testing categories.',
            'content' => str_repeat('This is valid article content. ', 10),
            'categories' => $categories->pluck('id')->all(),
        ]);

        $response->assertCreated();

        $article = Article::query()
            ->where('slug', 'category-assignment-test-article')
            ->firstOrFail();

        $this->assertDatabaseHas('article_categories', [
            'article_id' => $article->id,
            'category_id' => $categories[0]->id,
        ]);

        $this->assertDatabaseHas('article_categories', [
            'article_id' => $article->id,
            'category_id' => $categories[1]->id,
        ]);
    }


    public function test_article_categories_can_be_updated(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('author');

        $article = Article::factory()->create([
            'author_id' => $user->id,
        ]);

        $oldCategory = Category::factory()->create();
        $newCategory = Category::factory()->create();

        $article->categories()->attach($oldCategory);

        $this->actingAs($user);

        $response = $this->putJson("/articles/{$article->id}", [
            'categories' => [$newCategory->id],
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseMissing('article_categories', [
            'article_id' => $article->id,
            'category_id' => $oldCategory->id,
        ]);

        $this->assertDatabaseHas('article_categories', [
            'article_id' => $article->id,
            'category_id' => $newCategory->id,
        ]);
    }

    public function test_article_can_be_created_with_subcategories(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('author');

        $category = Category::factory()->create();
        $subcategories = Subcategory::factory()
            ->count(2)
            ->create([
                'category_id' => $category->id,
            ]);

        $this->actingAs($user);

        $response = $this->postJson('/articles', [
            'title' => 'Subcategory Assignment Test Article',
            'slug' => 'subcategory-assignment-test-article',
            'excerpt' => 'This is a valid article excerpt for testing subcategories.',
            'content' => str_repeat('This is valid article content. ', 10),
            'categories' => [$category->id],
            'subcategories' => $subcategories->pluck('id')->all(),
        ]);

        $response->assertCreated();

        $article = Article::query()
            ->where('slug', 'subcategory-assignment-test-article')
            ->firstOrFail();

        $this->assertDatabaseHas('article_subcategories', [
            'article_id' => $article->id,
            'subcategory_id' => $subcategories[0]->id,
        ]);

        $this->assertDatabaseHas('article_subcategories', [
            'article_id' => $article->id,
            'subcategory_id' => $subcategories[1]->id,
        ]);
    }

    public function test_article_subcategories_can_be_updated(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('author');

        $category = Category::factory()->create();

        $oldSubcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $newSubcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $article = Article::factory()->create([
            'author_id' => $user->id,
        ]);

        $article->categories()->attach($category);
        $article->subcategories()->attach($oldSubcategory);

        $this->actingAs($user);

        $response = $this->putJson("/articles/{$article->id}", [
            'subcategories' => [$newSubcategory->id],
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseMissing('article_subcategories', [
            'article_id' => $article->id,
            'subcategory_id' => $oldSubcategory->id,
        ]);

        $this->assertDatabaseHas('article_subcategories', [
            'article_id' => $article->id,
            'subcategory_id' => $newSubcategory->id,
        ]);
    }
}
