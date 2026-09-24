<?php

namespace Database\Factories\UserManagement;

use App\Modules\UserManagement\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'bio' => $this->faker->sentence(10),
            'avatar_url' => $this->faker->imageUrl(200, 200),
            'cover_image_url' => $this->faker->imageUrl(1200, 400),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other', 'prefer_not_to_say']),
            'phone_number' => $this->faker->phoneNumber(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'website' => $this->faker->url(),
            'twitter_handle' => $this->faker->userName(),
            'facebook_url' => 'https://facebook.com/' . $this->faker->userName(),
            'linkedin_url' => 'https://linkedin.com/in/' . $this->faker->userName(),
            'github_username' => $this->faker->userName(),
            'instagram_handle' => $this->faker->userName(),
            'is_public_profile' => $this->faker->boolean(70),
        ];
    }
}
