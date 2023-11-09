<?php

namespace App\Salesforce\Actions;

use App\Salesforce\BillingAddress;

class StoreAccount
{
    protected BillingAddress $billingAddress;
    protected string $phone;
    protected string $email;
    protected string $fullLegalName;
    protected string $referrerName;
    protected string $authorizeNetProfileId;
    protected string $authorizeNetPaymentProfileId;

    public function __construct(
        BillingAddress $billingAddress,
        string $phone,
        string $email,
        string $fullLegalName,
        string $referrerName,
        string $authorizeNetProfileId,
        string $authorizeNetPaymentProfileId
    ) {
        $this->billingAddress = $billingAddress;
        $this->phone = $phone;
        $this->email = $email;
        $this->fullLegalName = $fullLegalName;
        $this->referrerName = $referrerName;
        $this->authorizeNetProfileId = $authorizeNetProfileId;
        $this->authorizeNetPaymentProfileId = $authorizeNetPaymentProfileId;
    }

    public function getAuthorizeNetProfileId(): string
    {
        return $this->authorizeNetProfileId;
    }

    public function getAuthorizeNetPaymentProfileId(): string
    {
        return $this->authorizeNetPaymentProfileId;
    }

    public function getBillingAddress(): BillingAddress
    {
        return $this->billingAddress;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFullLegalName(): string
    {
        return $this->fullLegalName;
    }

    public function getReferrerName(): string
    {
        return $this->referrerName;
    }

    public function toApiPayload(): array
    {
        return [
            'AccountSource' => 'DL2',
            'BillingStreet' => $this->billingAddress->getStreet(),
            'BillingCity' => $this->billingAddress->getCity(),
            'BillingState' => $this->billingAddress->getState(),
            'BillingCountry' => $this->billingAddress->getCountry(),
            'BillingPostalCode' => $this->billingAddress->getPostalCode(),
            'Phone' => $this->getPhone(),
            'EMAIL__c' => $this->getEmail(),
            'Name' => $this->getFullLegalName(),
            'Full_Legal_Name__c' => $this->getFullLegalName(),
            'Referrer_Name__c' => $this->getReferrerName(),
            'T_Shirt_Size__c' => '',
            'customerProfileId__c' => $this->getAuthorizeNetProfileId(),
            'Payment_Profile_Id__c' => $this->getAuthorizeNetPaymentProfileId(),
        ];
    }
}
