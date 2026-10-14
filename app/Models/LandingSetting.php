<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingSetting extends Model
{
    use HasFactory;

    protected $table = 'landing_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    public static function getAllGrouped()
    {
        return static::all()->groupBy('group');
    }

    public static function getAllKeyValues()
    {
        return static::all()->pluck('value', 'key')->toArray();
    }
}
