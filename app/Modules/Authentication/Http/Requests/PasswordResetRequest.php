<?php

namespace App\Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email:rfc,dns'],
            'password' => [
                'required',
                'confirmed',
                Password::min(config('authentication.password.min_length'))
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'রিসেট টোকেন প্রয়োজন',
            'email.required' => 'ইমেইল প্রয়োজন',
            'password.required' => 'নতুন পাসওয়ার্ড প্রয়োজন',
        ];
    }
}