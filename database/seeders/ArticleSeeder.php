<?php

namespace Database\Seeders;

use App\Modules\Article\Models\Article;
use App\Modules\Article\Models\Category;
use App\Modules\Article\Models\Tag;
use App\Modules\Authentication\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::factory(5)->create();
        $tags = Tag::factory(15)->create();

        $authors = User::whereHas('roles', fn($q) => $q->whereIn('name', ['author', 'editor', 'admin']))->get();

        if ($authors->isEmpty()) return;

        Article::factory(50)
            ->state(fn() => ['author_id' => $authors->random()->id])
            ->afterCreating(fn(Article $article) => 
                $article->categories()->attach($categories->random(2)->pluck('id')) &&
                $article->tags()->attach($tags->random(3)->pluck('id'))
            )
            ->create();
    }
}
