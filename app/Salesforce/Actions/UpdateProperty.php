<?php

namespace App\Salesforce\Actions;

use Illuminate\Support\Carbon;

class UpdateProperty
{
    protected string $buyer;
    protected Carbon $dateOfSale;
    protected string $email;
    protected string $mergedStatus;

    public function __construct(string $buyer, string $mergedStatus, string $email, Carbon $dateOfSale)
    {
        $this->buyer = $buyer;
        $this->mergedStatus = $mergedStatus;
        $this->email = $email;
        $this->dateOfSale = $dateOfSale;
    }

    public function getBuyer(): string
    {
        return $this->buyer;
    }

    public function getMergedStatus(): string
    {
        return $this->mergedStatus;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function toApiPayload(): array
    {
        return [
            'Buyer__c' => $this->getBuyer(),
            'Merged_Status__C' => $this->getMergedStatus(),
            'Email__c' => $this->getEmail(),
            'Date_of_Sale__c' => $this->dateOfSale->toDateString(),
            'Payment_Day_Of_Months__c' => $this->dateOfSale->day,
        ];
    }
}