<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Article\Models\Article;
use App\Modules\Authentication\Models\User;
use App\Modules\Article\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(10, true),
            'featured_image' => $this->faker->imageUrl(1200, 630),
            'status' => ArticleStatus::PUBLISHED,
            'featured' => $this->faker->boolean(20),
            'author_id' => User::factory(),
            'published_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'meta_title' => $this->faker->sentence(5),
            'meta_description' => $this->faker->paragraph(1),
            'views_count' => $this->faker->numberBetween(0, 5000),
        ];
    }

    public function draft(): self
    {
        return $this->state(fn($attr) => ['status' => ArticleStatus::DRAFT, 'published_at' => null]);
    }

    public function published(): self
    {
        return $this->state(fn($attr) => ['status' => ArticleStatus::PUBLISHED]);
    }

    public function featured(): self
    {
        return $this->published()->state(fn($attr) => ['featured' => true]);
    }
}


