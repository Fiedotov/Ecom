<?php

namespace App\Salesforce;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $contract_id
 */
class Contract extends Model
{
    use HasFactory;

    protected $table = 'salesforce_contracts';
    protected $guarded = [];
    protected $casts = [
        'data' => 'object',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'contract_id', 'contract_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
