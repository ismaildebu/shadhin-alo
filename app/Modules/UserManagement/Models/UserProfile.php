<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Models;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'bio',
        'avatar_url',
        'cover_image_url',
        'date_of_birth',
        'gender',
        'phone_number',
        'country',
        'city',
        'state',
        'postal_code',
        'address',
        'website',
        'twitter_handle',
        'facebook_url',
        'linkedin_url',
        'github_username',
        'instagram_handle',
        'is_public_profile',
        'last_profile_update',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_public_profile' => 'bool',
        'last_profile_update' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    public function updateLastProfileUpdate(): void
    {
        $this->update(['last_profile_update' => now()]);
    }
}
