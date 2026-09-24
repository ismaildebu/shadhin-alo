<?php

namespace Database\Seeders;

use App\Modules\Comment\Models\Comment;
use App\Modules\Article\Models\Article;
use App\Modules\Authentication\Models\User;
use App\Modules\Comment\Enums\CommentStatus;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $articles = Article::published()->get();
        $users = User::all();

        if ($articles->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($articles as $article) {
            // Create 3-8 comments per article
            Comment::factory(rand(3, 8))
                ->state(fn() => [
                    'commentable_type' => 'App\Modules\Article\Models\Article',
                    'commentable_id' => $article->id,
                    'user_id' => $users->random()->id,
                    'status' => CommentStatus::APPROVED,
                ])
                ->create();

            // Create replies for some comments
            Comment::where('commentable_id', $article->id)
                ->inRandomOrder()
                ->limit(rand(0, 3))
                ->each(function (Comment $parent) use ($users) {
                    Comment::factory(rand(1, 3))
                        ->state(fn() => [
                            'parent_id' => $parent->id,
                            'commentable_type' => $parent->commentable_type,
                            'commentable_id' => $parent->commentable_id,
                            'user_id' => $users->random()->id,
                            'status' => CommentStatus::APPROVED,
                        ])
                        ->create();
                });
        }
    }
}
