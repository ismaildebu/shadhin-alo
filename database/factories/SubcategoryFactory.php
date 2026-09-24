<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Article\Models\Category;
use App\Modules\Article\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(300, 200),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}