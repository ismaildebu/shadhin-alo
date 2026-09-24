<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:roles'],
            'slug' => ['required', 'string', 'max:100', 'unique:roles', 'alpha_dash'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Role name is required',
            'name.unique' => 'This role name already exists',
            'slug.required' => 'Role slug is required',
            'slug.unique' => 'This role slug already exists',
            'slug.alpha_dash' => 'Role slug must contain only letters, numbers, dashes and underscores',
        ];
    }
}
