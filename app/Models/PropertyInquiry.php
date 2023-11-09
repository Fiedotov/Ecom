<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyInquiry extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'buy_reasons' => 'array',
        'contact_allowed' => 'boolean',
        'spanish' => 'boolean',
        'tracking' => 'object',
    ];

    public function toApiPayload(): array
    {
        return [
            'FirstName' => $this->getAttribute('first_name'),
            'LastName' => $this->getAttribute('last_name'),
            'Email' => $this->getAttribute('email'),
            'Phone' => $this->getAttribute('phone'),
            'Why_do_you_want_to_buy_land__c' => implode(',', $this->getAttribute('buy_reasons')),
            'Other_Questions__c' => $this->getAttribute('question'),
            'Preferred_Language__c' => $this->getAttribute('spanish') ? 'spanish' : '',
            'TCPA_Accepted__c' => $this->getAttribute('contact_allowed'),
        ];
    }
}
