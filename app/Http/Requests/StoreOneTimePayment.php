<?php

namespace App\Http\Requests;

use App\Salesforce\Payment;
use Illuminate\Foundation\Http\FormRequest;

class StoreOneTimePayment extends FormRequest
{
    public function rules(): array
    {
        return [
            'profile_id' => ['required', 'string'],
            'payment_id' => ['required', 'string'],
        ];
    }

    public function amount(): float
    {
        return $this->payment()->outstandingBalance();
    }

    public function paymentProfile(): array
    {
        return [
            'customerProfileId' => $this->user()->authorize_net_profile_id,
            'paymentProfile' => ['paymentProfileId' => $this->input('profile_id')],
        ];
    }

    public function lineItems(): array
    {
        return [
            'lineItem' => [
                'itemId' => $this->payment()->payment_id,
                'name' => 'Contract Payment Installment',
                'description' => sprintf('Payment installment for contract %s', $this->payment()->contract_id),
                'quantity' => '1',
                'unitPrice' => $this->amount(),
            ]
        ];
    }

    public function payment(): ?Payment
    {
        return $this->user()->payments()->where('payment_id', $this->input('payment_id'))->first();
    }
}
