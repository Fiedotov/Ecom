<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Collection $recipients
 */
class Notification extends Model
{
    use HasFactory;

    public const INTERNAL_ORDER_CONFIRMATION = 'Internal Order Confirmation';

    protected $table = 'notifications';
    protected $guarded = [];

    public function recipients(): HasMany
    {
        return $this->hasMany(Recipient::class, 'notification_id', 'id');
    }

    public static function getByName(string $name): ?static
    {
        return static::query()->where('name', $name)->first();
    }
}
