<?php

namespace App\Salesforce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property object $authorize_response
 * @property int $id
 * @property Payment $payment
 * @property string $payment_id
 * @property object $salesforce_response
 */
class PaymentInstallment extends Model
{
    use HasFactory;

    protected $table = 'salesforce_payment_installments';
    protected $guarded = [];
    protected $casts = [
        'authorize_response' => 'object',
        'salesforce_response' => 'object',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }
}
