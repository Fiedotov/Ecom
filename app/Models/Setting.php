<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

/**
 * @property string $key
 * @property object $value
 */
class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $guarded = [];
    protected $casts = [
        'value' => 'object'
    ];

    public static function getByKey(string $key): ?self
    {
        return self::query()->where('key', $key)->first();
    }
}
