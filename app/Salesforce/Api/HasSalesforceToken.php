<?php

namespace App\Salesforce\Api;

trait HasSalesforceToken
{
    protected ?string $sfToken = null;

    protected function getSalesforceToken(): string
    {
        if (is_null($this->sfToken)) {
            $this->sfToken = (new GenerateApiToken())();
        }

        return $this->sfToken;
    }
}