<?php

namespace App\Salesforce\Actions;

use App\Models\PaymentSubmission;

class StoreContract
{
    protected PaymentSubmission $submission;

    public function __construct(PaymentSubmission $submission)
    {
        $this->submission = $submission;
    }

    public function toApiPayload(): array
    {
        return [
            'AccountId' => $this->submission->getSalesforceAccountId(),
            'Buyer__c' => $this->submission->getSalesforceContactId(),
            'APN__c' => $this->submission->property->apn,
            'BillingCity' => $this->submission->billingAddress()->getCity(),
            'BillingState' => $this->submission->billingAddress()->getState(),
            'BillingStreet' => $this->submission->billingAddress()->getStreet(),
            'BillingPostalCode' => $this->submission->billingAddress()->getPostalCode(),
            'Category__c' => $this->submission->getPaymentCount() == 1 ? 'Cash - PIF' : 'Active',
            'CC_Last_4__c' => $this->submission->getCcLast4(),
            'Down_Paymount__c' => $this->getDownPayment(),
            'Down_paymount_Doc_Fee__c' => $this->getDownPaymentWithDocFee(),
            'Email__c' => $this->submission->payload->customer->email,
            'EndDate' => $this->submission->getContractEndDate()->toDateString(),
            'Legal_Description__c' => $this->submission->property->legal_description,
            'Monthly_Payment__c' => $this->submission->getMonthlyPaymentAmount(),
            'Payment_Day_of_Month__c' => $this->submission->getPaymentDayOfMonth(),
            'StartDate' => $this->submission->getContractStartDate()->toDateString(),
            'ContractTerm' => $this->submission->getPaymentCount(),
            'Total_Month__c' => $this->submission->getPaymentCount(),
            'Property__c' => $this->submission->property->property_id,
            'Contract_Years__c' => floor($this->submission->getPaymentCount() / 12),
            'Contract_Months__c' => $this->submission->getPaymentCount() % 12,
            'Promotion_Id__c' => $this->submission->payload?->promotionId ?? '',
            'Promotion_Label__c' => $this->submission->payload?->promotionLabel ?? '',
            'Promotional_Discount__c' => $this->submission->payload?->discountAmount ?? '',
            'Marked_Price__c' => $this->submission->payload?->markedPrice ?? '',
            'Generated_from__c' => 'DL2'
        ];
    }

    private function getCountyState(): string
    {
        return sprintf('%s/%s', $this->submission->property->county, $this->submission->property->state);
    }

    private function getCashPrice(): string
    {
        return number_format($this->submission->property->cash_price_current, 2, '.', '');
    }

    private function getDownPayment(): float
    {
        return match ($this->submission->getPaymentCount()) {
            1 => $this->getCashPrice(),
            default => $this->submission->property->getDownPayment(),
        };
    }

    private function getDownPaymentWithDocFee(): float
    {
        return match ($this->submission->getPaymentCount()) {
            1 => round($this->submission->property->cash_price_current + config('discount.document_fee'), 2),
            default => $this->submission->getFees(),
        };
    }
}
