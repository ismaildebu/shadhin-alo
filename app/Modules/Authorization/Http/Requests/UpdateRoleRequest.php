<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $role = $this->route('role');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('roles')->ignore($role->id),
            ],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ];
    }
}
