<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'order',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        Cache::forget('setting_' . $key);
    }

    public static function getGroup(string $group): array
    {
        return Cache::rememberForever('settings_group_' . $group, function () use ($group) {
            return static::where('group', $group)
                ->orderBy('order')
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public static function clearCache(): void
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget('setting_' . $setting->key);
        }
        Cache::forget('settings_group_general');
        Cache::forget('settings_group_seo');
        Cache::forget('settings_group_social');
        Cache::forget('settings_group_scripts');
        Cache::forget('settings_group_contact');
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget('setting_' . $setting->key);
            Cache::forget('settings_group_' . $setting->group);
        });

        static::deleted(function ($setting) {
            Cache::forget('setting_' . $setting->key);
            Cache::forget('settings_group_' . $setting->group);
        });
    }
}
