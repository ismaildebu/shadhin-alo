<?php

declare(strict_types=1);

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'email_settings';

    protected $fillable = [
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'mailgun_domain',
        'mailgun_secret',
        'sendgrid_api_key',
        'ses_key',
        'ses_secret',
        'ses_region',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'mail_password',
        'mailgun_secret',
        'sendgrid_api_key',
        'ses_key',
        'ses_secret',
    ];

    public function isConfigured(): bool
    {
        return $this->is_active && !empty($this->mail_driver);
    }

    public function toEnv(): array
    {
        return [
            'MAIL_DRIVER' => $this->mail_driver,
            'MAIL_HOST' => $this->mail_host,
            'MAIL_PORT' => $this->mail_port,
            'MAIL_USERNAME' => $this->mail_username,
            'MAIL_PASSWORD' => $this->mail_password,
            'MAIL_ENCRYPTION' => $this->mail_encryption,
            'MAIL_FROM_ADDRESS' => $this->mail_from_address,
            'MAIL_FROM_NAME' => $this->mail_from_name,
        ];
    }
}
