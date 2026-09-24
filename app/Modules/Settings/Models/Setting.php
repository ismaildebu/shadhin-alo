<?php

declare(strict_types=1);

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'module',
        'is_public',
        'is_encrypted',
        'validation_rule',
    ];

    protected $casts = [
        'is_public' => 'bool',
        'is_encrypted' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, ?string $type = null): self
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $type ?? 'string']);
    }

    public static function getByModule(string $module): array
    {
        return self::where('module', $module)->pluck('value', 'key')->toArray();
    }
}
