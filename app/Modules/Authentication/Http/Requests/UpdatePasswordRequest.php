<?php

namespace App\Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(config('authentication.password.min_length'))
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'different:current_password',
            ],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'বর্তমান পাসওয়ার্ড প্রয়োজন',
            'current_password.current_password' => 'বর্তমান পাসওয়ার্ড সঠিক নয়',
            'password.required' => 'নতুন পাসওয়ার্ড প্রয়োজন',
            'password.different' => 'নতুন পাসওয়ার্ড পুরানোটির থেকে আলাদা হতে হবে',
        ];
    }
}