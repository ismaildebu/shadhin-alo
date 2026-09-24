<?php

namespace App\Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * অনুমোদন করুন
     */
    public function authorize(): bool
    {
        return true; // সবাই নিবন্ধন করতে পারে
    }

    /**
     * বৈধতা নিয়ম পান
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s\-\x{0980}-\x{09FF}]+$/u', // বাংলা এবং ইংরেজি অক্ষর
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s\-\x{0980}-\x{09FF}]+$/u',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'phone:BD,US',
                'unique:users,phone',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(config('authentication.password.min_length'))
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'password_confirmation' => [
                'required',
            ],
            'gdpr_consent' => [
                'required',
                'accepted',
            ],
            'marketing_consent' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * বৈধতা বার্তা পান
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'প্রথম নাম প্রয়োজন',
            'first_name.regex' => 'প্রথম নামে শুধুমাত্র অক্ষর, স্থান এবং হাইফেন থাকতে পারে',
            'last_name.required' => 'শেষ নাম প্রয়োজন',
            'last_name.regex' => 'শেষ নামে শুধুমাত্র অক্ষর, স্থান এবং হাইফেন থাকতে পারে',
            'email.required' => 'ইমেইল প্রয়োজন',
            'email.email' => 'বৈধ ইমেইল প্রবেশ করুন',
            'email.unique' => 'এই ইমেইল ইতিমধ্যে নিবন্ধিত',
            'password.required' => 'পাসওয়ার্ড প্রয়োজন',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে',
            'gdpr_consent.required' => 'GDPR শর্তাবলী গ্রহণ করা আবশ্যক',
        ];
    }

    /**
     * পরিশোধিত ডেটা প্রস্তুত করুন
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email),
            'phone' => $this->phone ? str_replace([' ', '-', '(', ')'], '', $this->phone) : null,
        ]);
    }
}