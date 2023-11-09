<?php

namespace App\Salesforce\Api;

class Account extends Entity
{
    public function getName(): string
    {
        return $this->getAttribute('Name');
    }
}