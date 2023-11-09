<?php

namespace App\Salesforce;

class BillingAddress
{
    protected string $street;
    protected string $city;
    protected string $state;
    protected string $postalCode;
    protected string $country;

    public function __construct(string $street, string $city, string $state, string $postalCode, string $country)
    {
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}