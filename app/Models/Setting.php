<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /**
     * Nyala/mati blok quotes di halaman login.
     */
    public const LOGIN_QUOTE_ENABLED = 'login.quote_enabled';

    protected $table = 'settings';

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", now()->addMinutes(5), function () use ($key, $default) {
            return static::query()->where('key', $key)->value('value') ?? $default;
        });
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forget("setting:{$key}");
    }

    public static function enabled(string $key, bool $default = true): bool
    {
        return filter_var(static::get($key, $default ? '1' : '0'), FILTER_VALIDATE_BOOLEAN);
    }
}
