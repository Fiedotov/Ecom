<?php

namespace App\AuthorizeNet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 */
class CustomerProfile extends Model
{
    use HasFactory;

    protected $table = 'customer_profiles';
    protected $guarded = [];
    protected $casts = [
        'data' => 'object',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
