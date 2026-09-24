<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'bio' => ['sometimes', 'string', 'max:1000'],
            'avatar_url' => ['sometimes', 'image', 'max:2048'],
            'cover_image_url' => ['sometimes', 'image', 'max:5120'],
            'date_of_birth' => ['sometimes', 'date'],
            'gender' => ['sometimes', 'in:male,female,other,prefer_not_to_say'],
            'phone_number' => ['sometimes', 'string', 'max:20'],
            'country' => ['sometimes', 'string', 'max:100'],
            'city' => ['sometimes', 'string', 'max:100'],
            'state' => ['sometimes', 'string', 'max:100'],
            'postal_code' => ['sometimes', 'string', 'max:20'],
            'address' => ['sometimes', 'string', 'max:500'],
            'website' => ['sometimes', 'url', 'max:255'],
            'twitter_handle' => ['sometimes', 'string', 'max:50', 'regex:/^[A-Za-z0-9_]{1,50}$/'],
            'facebook_url' => ['sometimes', 'url'],
            'linkedin_url' => ['sometimes', 'url'],
            'github_username' => ['sometimes', 'string', 'max:100'],
            'instagram_handle' => ['sometimes', 'string', 'max:50', 'regex:/^[A-Za-z0-9_.]{1,50}$/'],
            'is_public_profile' => ['sometimes', 'boolean'],
        ];
    }
}
