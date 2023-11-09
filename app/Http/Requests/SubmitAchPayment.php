<?php

namespace App\Http\Requests;

use App\AuthorizeNet\AchPaymentRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitAchPayment extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_number' => ['required'],
            'account_type' => ['required', 'string', Rule::in(['checking', 'savings', 'businessChecking'])],
            'amount' => ['required'],
            'bank_name' => ['required', 'string'],
            'name_on_account' => ['required', 'string'],
            'routing_number' => ['required', 'string'],
            'payment_id' => ['required', Rule::exists('salesforce_payments', 'id')],
        ];
    }

    public function request(): AchPaymentRequest
    {
        return (new AchPaymentRequest($this->input('amount')))
            ->setAccountNumber($this->input('account_number'))
            ->setAccountType($this->input('account_type'))
            ->setBankName($this->input('bank_name'))
            ->setEcheckType('WEB')
            ->setNameOnAccount($this->input('name_on_account'))
            ->setRoutingNumber($this->input('routing_number'))
            ->setInvoiceNumber('INV-12345')
            ->setDescription('Test ACH Payment');
    }
}