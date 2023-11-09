<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Omnipay\Common\CreditCard;

class StorePaymentMethod extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'dataDescriptor' => ['required', 'string'],
            'dataValue' => ['required', 'string'],
        ];
    }

    public function card(): array
    {
        return [
            'opaqueDataDescriptor' => $this->input('dataDescriptor'),
            'opaqueDataValue' => $this->input('dataValue'),
            'email' => $this->user()->getAttribute('email'),
            'customerProfileId' => $this->user()->getAttribute('authorize_net_profile_id'),
            'card' => new CreditCard([
                'name' => sprintf('%s %s', $this->input('first_name'), $this->input('last_name')),
                'firstName' => $this->input('first_name'),
                'lastName' => $this->input('last_name'),
                'address' => $this->input('address'),
                'city' => $this->input('city'),
                'state' => $this->input('state'),
                'zip' => $this->input('postal_code'),
                'country' => 'US',
            ])
        ];
    }
}
