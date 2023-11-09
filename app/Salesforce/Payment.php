<?php

namespace App\Salesforce;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;

/**
 * @property string $contract_id
 * @property int $id
 * @property Collection $installments
 * @property string $payment_id
 * @property ?User $user
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'salesforce_payments';
    protected $guarded = [];
    protected $casts = [
        'data' => 'object',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'contract_id');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class, 'payment_id', 'payment_id');
    }

    public function user():  HasOneThrough
    {
        return $this->hasOneThrough(User::class, Contract::class, 'contract_id', 'id', 'contract_id', 'user_id');
    }

    public function outstandingBalance(): float
    {
        return $this->data?->Total_Outstanding_Amount_with_Fees__c;
    }
}
