<?php

declare(strict_types=1);

namespace Tests\Feature\Comment;

use App\Modules\Article\Models\Article;
use App\Modules\Authentication\Models\User;
use App\Modules\Comment\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_rate_a_comment(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $comment = Comment::create([
            'content' => 'Test comment',
            'status' => 'approved',
            'user_id' => $user->id,
            'commentable_type' => Article::class,
            'commentable_id' => $article->id,
        ]);

        $response = $this->postJson('/comments/ratings', [
            'comment_id' => $comment->id,
            'article_id' => $article->id,
            'rating' => 5,
        ]);

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_rate_a_comment(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $comment = Comment::create([
            'content' => 'Test comment',
            'status' => 'approved',
            'user_id' => $user->id,
            'commentable_type' => Article::class,
            'commentable_id' => $article->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/comments/ratings', [
                'comment_id' => $comment->id,
                'article_id' => $article->id,
                'rating' => 5,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('comment_ratings', [
            'comment_id' => $comment->id,
            'article_id' => $article->id,
            'user_id' => $user->id,
            'rating' => 5,
        ]);
    }

    public function test_same_user_updates_existing_rating_instead_of_creating_duplicate(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $comment = Comment::create([
            'content' => 'Test comment',
            'status' => 'approved',
            'user_id' => $user->id,
            'commentable_type' => Article::class,
            'commentable_id' => $article->id,
        ]);

        $this
            ->actingAs($user, 'sanctum')
            ->postJson('/comments/ratings', [
                'comment_id' => $comment->id,
                'article_id' => $article->id,
                'rating' => 3,
            ])
            ->assertOk();

        $this
            ->actingAs($user, 'sanctum')
            ->postJson('/comments/ratings', [
                'comment_id' => $comment->id,
                'article_id' => $article->id,
                'rating' => 5,
            ])
            ->assertOk();

        $this->assertDatabaseCount('comment_ratings', 1);

        $this->assertDatabaseHas('comment_ratings', [
            'comment_id' => $comment->id,
            'article_id' => $article->id,
            'user_id' => $user->id,
            'rating' => 5,
        ]);
    }

    public function test_rating_rejects_comment_from_another_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $anotherArticle = Article::factory()->create();

        $comment = Comment::create([
            'content' => 'Test comment',
            'status' => 'approved',
            'user_id' => $user->id,
            'commentable_type' => Article::class,
            'commentable_id' => $article->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/comments/ratings', [
                'comment_id' => $comment->id,
                'article_id' => $anotherArticle->id,
                'rating' => 5,
            ]);

        $response->assertUnprocessable();

        $this->assertDatabaseCount('comment_ratings', 0);
    }

    public function test_rating_must_be_between_one_and_five(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $comment = Comment::create([
            'content' => 'Test comment',
            'status' => 'approved',
            'user_id' => $user->id,
            'commentable_type' => Article::class,
            'commentable_id' => $article->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/comments/ratings', [
                'comment_id' => $comment->id,
                'article_id' => $article->id,
                'rating' => 6,
            ]);

        $response->assertUnprocessable();

        $this->assertDatabaseCount('comment_ratings', 0);
    }
}
