<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => 'Permissions are required',
            'permissions.array' => 'Permissions must be an array',
            'permissions.*.string' => 'Each permission must be a valid slug',
            'permissions.*.exists' => 'One or more selected permissions do not exist',
        ];
    }
}