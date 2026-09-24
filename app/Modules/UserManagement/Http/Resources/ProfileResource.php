<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'full_name' => $this->getFullName(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'bio' => $this->bio,
            'avatar_url' => $this->avatar_url,
            'cover_image_url' => $this->cover_image_url,
            'date_of_birth' => $this->date_of_birth,
            'age' => $this->age,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'website' => $this->website,
            'social_links' => [
                'twitter' => $this->twitter_handle,
                'facebook' => $this->facebook_url,
                'linkedin' => $this->linkedin_url,
                'github' => $this->github_username,
                'instagram' => $this->instagram_handle,
            ],
            'is_public_profile' => $this->is_public_profile,
            'last_profile_update' => $this->last_profile_update,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
