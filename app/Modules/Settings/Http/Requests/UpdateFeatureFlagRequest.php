<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureFlagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'slug' => ['sometimes', 'string', 'max:100', 'unique:feature_flags,slug'],
            'description' => ['sometimes', 'string'],
            'is_enabled' => ['sometimes', 'boolean'],
            'rollout_percentage' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'target_users' => ['sometimes', 'array'],
            'target_roles' => ['sometimes', 'array'],
            'metadata' => ['sometimes', 'array'],
        ];
    }
}
