<?php

namespace Database\Factories;

use App\Modules\Authorization\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle(),
            'slug' => $this->faker->unique()->slug(2, false),
            'description' => $this->faker->sentence(),
            'is_system' => false,
            'is_active' => true,
            'guard_name' => 'web',
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function admin(): self
    {
        return $this->state([
            'name' => 'Administrator',
            'slug' => 'admin',
            'is_system' => true,
            'sort_order' => 1,
        ]);
    }
}