<?php

namespace App\Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Lockout;

class LoginRequest extends FormRequest
{
    /**
     * অনুমোদন করুন
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * বৈধতা নিয়ম
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'remember_me' => ['nullable', 'boolean'],
        ];
    }

    /**
     * বৈধতা বার্তা
     */
    public function messages(): array
    {
        return [
            'email.required' => 'ইমেইল প্রয়োজন',
            'email.email' => 'বৈধ ইমেইল প্রবেশ করুন',
            'password.required' => 'পাসওয়ার্ড প্রয়োজন',
        ];
    }

    /**
     * ব্যবহারকারীর লগইন rate limit পরীক্ষা করুন
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * থ্রটেল কী পান
     */
    public function throttleKey(): string
    {
        return Str::transliterate($this->string('email')) . '|' . $this->ip();
    }
}
