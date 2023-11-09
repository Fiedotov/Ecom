<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $url
 */
class PropertyImage extends Model
{
    use HasFactory;

    protected $table = 'property_images';
    protected $guarded = [];
    protected $appends = ['url'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function getUrlAttribute(): string
    {
        $url = $this->attributes['url'];

        return Str::startsWith($url, 'http') ? $url : asset("storage/property-images/$url");
    }
}
