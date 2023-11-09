<?php

namespace App\AuthorizeNet;

class AchPaymentRequest
{
    private int $amount;
    private string $accountType;
    private string $routingNumber;
    private string $accountNumber;
    private string $nameOnAccount;
    private string $echeckType;
    private string $bankName;
    private string $invoiceNumber;
    private string $description;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAccountType(string $accountType): AchPaymentRequest
    {
        $this->accountType = $accountType;
        return $this;
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function setRoutingNumber(string $routingNumber): AchPaymentRequest
    {
        $this->routingNumber = $routingNumber;
        return $this;
    }

    public function getRoutingNumber(): string
    {
        return $this->routingNumber;
    }

    public function setAccountNumber(string $accountNumber): AchPaymentRequest
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getNameOnAccount(): string
    {
        return $this->nameOnAccount;
    }

    public function getEcheckType(): string
    {
        return $this->echeckType;
    }

    public function getBankName(): string
    {
        return $this->bankName;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setNameOnAccount(string $nameOnAccount): AchPaymentRequest
    {
        $this->nameOnAccount = $nameOnAccount;
        return $this;
    }

    public function setEcheckType(string $echeckType): AchPaymentRequest
    {
        $this->echeckType = $echeckType;
        return $this;
    }

    public function setBankName(string $bankName): AchPaymentRequest
    {
        $this->bankName = $bankName;
        return $this;
    }

    public function setAmount(string $amount): AchPaymentRequest
    {
        $this->amount = $amount;
        return $this;
    }

    public function setInvoiceNumber(string $invoiceNumber): AchPaymentRequest
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    public function setDescription(string $description): AchPaymentRequest
    {
        $this->description = $description;
        return $this;
    }

    public function toPayload(): array
    {
        return [
            'transactionType' => 'authCaptureTransaction',
            'amount' => $this->getAmount(),
            'currencyCode' => 'USD',
            'payment' => [
                'bankAccount' => [
                    'accountType' => $this->getAccountType(),
                    'routingNumber' => $this->getRoutingNumber(),
                    'accountNumber' => $this->getAccountNumber(),
                    'nameOnAccount' => $this->getNameOnAccount(),
                    'echeckType' => 'WEB',
                    'bankName' => $this->getBankName(),
                ],
            ],
            'order' => [
                'invoiceNumber' => $this->getInvoiceNumber(),
                'description' => $this->getDescription()
            ],
        ];
    }
}