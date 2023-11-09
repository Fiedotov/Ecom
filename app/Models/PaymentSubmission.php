<?php

namespace App\Models;

use App\Salesforce\BillingAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use stdClass;

/**
 * @property stdClass $authorize_net_response
 * @property Carbon $created_at
 * @property int $id
 * @property stdClass $payload
 * @property Property $property
 * @property Carbon $updated_at
 */
class PaymentSubmission extends Model
{
    use HasFactory;

    protected $table = 'payment_submissions';
    protected $guarded = [];

    protected $casts = [
        'payload' => 'object',
        'authorize_net_response' => 'object',
        'authorize_net_response_profile' => 'object',
        'sf_account_response' => 'object',
        'sf_contact_response' => 'object',
        'sf_contract_response' => 'object',
        'sf_property_response' => 'object',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function isComplete(): bool
    {
        return !is_null($this->getAttribute('completed_at'));
    }

    public function getUser(): ?User
    {
        return User::findByEmail($this->getCustomerEmail());
    }

    public function getCustomerEmail(): string
    {
        return $this->payload->customer->email;
    }

    public function getSalesforceAccountId(): ?string
    {
        if (object_get($this->sf_account_response, 'id')) {
            return $this->sf_account_response->id;
        }

        if (! is_array($this->sf_account_response)) {
            return null;
        }

        return data_get($this->sf_account_response, '0.duplicateResut.matchResults.0.matchRecords.0.record.Id');
    }

    public function getSalesforceContactId(): ?string
    {
        return $this->sf_contact_response->Id ?? null;
    }

    public function getPaymentAmount(): float
    {
        return match ($this->getPaymentCount()) {
            1 => $this->getTotalPaymentAmount(),
            default => round($this->property->getDownPayment() + config('discount.document_fee'), 2),
        };
    }

    public function getTotalPaymentAmount(): float
    {
        $total = round($this->property->cash_price_current + config('discount.document_fee'), 2);
        $total -= $this->getDiscountAmount() ?? 0;

        return round($total, 2);
    }

    public function billingAddress(): BillingAddress
    {
        return new BillingAddress(
            trim(sprintf('%s %s', $this->payload->customer->address, $this->payload->customer->address2)),
            $this->payload->customer->city,
            $this->payload->customer->state,
            $this->payload->customer->postal_code,
            'US'
        );
    }

    public function getCcLast4(): string
    {
        return Str::substr(
            data_get($this->authorize_net_response, 'transactionResponse.accountNumber', ''),
            -4
        );
    }

    public function getPaymentCount(): ?int
    {
        return $this->payload->payments;
    }

    public function getDiscountAmount(): ?float
    {
        return $this->payload?->discountAmount ?? 0;
    }

    public function getContractStartDate(): Carbon
    {
        return $this->created_at->clone();
    }

    public function getContractEndDate(): Carbon
    {
        return $this->getContractStartDate()->clone()->addMonths($this->getPaymentCount());
    }

    public function getMonthlyPaymentAmount($withFees = false): float
    {
        // PIF (pay in full)
        if ($this->getPaymentCount() == 1) {
            return 0;
        }

        $payment = match ($this->getPaymentCount()) {
            $this->property->term_1 => $this->property->payment_1,
            $this->property->term_2 => $this->property->payment_2,
            $this->property->term_3 => $this->property->payment_3,
            default => 0.00
        } + ($withFees ? $this->property->getEscrowAndFees() : 0);

        $payment -= $this->getDiscountAmount() ?? 0; 

        return round($payment, 2);
    }

    public function getFees(): float
    {
        return round($this->property->getDownPayment() + $this->property->getDocumentFee(), 2);
    }

    public function getPaymentDayOfMonth(): int
    {
        return $this->created_at->day;
    }

    public function getTotalYears(): string
    {
        return (string)floor($this->getPaymentCount() / 12);
    }

    public function getAuthorizeNetPaymentProfileId(): ?string
    {
        return Arr::get(object_get($this->authorize_net_response_profile, 'customerPaymentProfileIdList', []), 0, '');
    }

    public function getTransactionId(): string
    {
        return object_get($this->authorize_net_response, 'transactionResponse.transId', '');
    }

    public function getConfirmationUrl(): string
    {
        return route('confirmation', ['transactionId' => $this->getTransactionId()]);
    }
}
