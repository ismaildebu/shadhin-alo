<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mail_driver' => ['required', 'in:smtp,sendmail,mailgun,postmark,sendgrid,ses'],
            'mail_host' => ['required_if:mail_driver,smtp', 'string', 'max:255'],
            'mail_port' => ['required_if:mail_driver,smtp', 'integer'],
            'mail_username' => ['sometimes', 'string', 'max:255'],
            'mail_password' => ['sometimes', 'string'],
            'mail_encryption' => ['sometimes', 'in:tls,ssl'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string', 'max:255'],
            'mailgun_domain' => ['sometimes', 'string'],
            'mailgun_secret' => ['sometimes', 'string'],
            'sendgrid_api_key' => ['sometimes', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
