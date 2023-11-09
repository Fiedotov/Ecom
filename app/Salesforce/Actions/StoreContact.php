<?php

namespace App\Salesforce\Actions;

class StoreContact
{
    protected string $accountId;
    protected string $phone;
    protected string $email;
    protected string $firstName;
    protected string $lastName;

    public function __construct(string $accountId, string $phone, string $email, string $firstName, string $lastName)
    {
        $this->accountId = $accountId;
        $this->phone = $phone;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function toApiPayload(): array
    {
        return [
            'accountId' => $this->getAccountId(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
        ];
    }
}