<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mail_driver' => $this->mail_driver,
            'mail_host' => $this->mail_host,
            'mail_port' => $this->mail_port,
            'mail_username' => $this->mail_username,
            'mail_encryption' => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name' => $this->mail_from_name,
            'is_active' => $this->is_active,
            'is_configured' => $this->isConfigured(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
