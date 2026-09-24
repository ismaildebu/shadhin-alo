<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required'],
            'type' => ['sometimes', 'in:string,integer,boolean,array,json'],
            'description' => ['sometimes', 'string', 'max:500'],
        ];
    }
}
