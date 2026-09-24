<?php

namespace Database\Factories;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'password' => 'password',
            'email_verified_at' => now(),
            'status' => 'active',
            'activated_at' => now(),
            'role' => 'subscriber',
            'timezone' => 'UTC',
            'language' => 'bn',
            'theme' => 'auto',
            'remember_token' => Str::random(10),
            'gdpr_accepted_at' => now(),
        ];
    }

    /**
     * Verified user
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Unverified user
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Admin user
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Author user
     */
    public function author(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'author',
        ]);
    }
}
