<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Article\Models\Article;
use App\Modules\Authentication\Models\User;
use App\Modules\Comment\Models\Comment;
use App\Modules\Comment\Enums\CommentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement([
                CommentStatus::PENDING,
                CommentStatus::APPROVED,
            ]),
            'user_id' => User::factory(),
            'parent_id' => null,
            'commentable_type' => Article::class,
            'commentable_id' => Article::factory(),
            'helpful_count' => $this->faker->numberBetween(0, 100),
            'unhelpful_count' => $this->faker->numberBetween(0, 20),
        ];
    }

    public function approved(): self
    {
        return $this->state(fn ($attributes) => [
            'status' => CommentStatus::APPROVED,
        ]);
    }

    public function pending(): self
    {
        return $this->state(fn ($attributes) => [
            'status' => CommentStatus::PENDING,
        ]);
    }

    public function spam(): self
    {
        return $this->state(fn ($attributes) => [
            'status' => CommentStatus::SPAM,
        ]);
    }
}
