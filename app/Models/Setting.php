<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get a setting value by key.
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue($key, $value, $type = 'string')
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->type = $type;
        $setting->save();

        return $setting;
    }

    /**
     * Cast the value based on type.
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'array':
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}