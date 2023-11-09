<?php

namespace App\Http\Requests;

use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class Checkout extends FormRequest
{
    const TYPE_FULL = 'full';
    const TYPE_MONTHLY = 'monthly';

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in([static::TYPE_FULL, static::TYPE_MONTHLY])
            ],
            'payment_count' => [
                'required',
                Rule::when($this->paymentCount() === 1, Rule::in([1])),
                Rule::when(
                    $this->paymentCount() !== 1,
                    Rule::in($this->getProperty()->only(['term_1', 'term_2', 'term_3']))
                ),
            ],
        ];
    }

    public function lineItems(): array
    {
        return match ($this->getType()) {
            static::TYPE_FULL => [
                ['name' => 'Initial Payment', 'amount' => $this->getProperty()->cash_price_current],
                ['name' => 'Document Fee', 'amount' => config('discount.document_fee')],
            ],
            default => [
                ['name' => 'Down Payment', 'amount' => $this->getProperty()->getDownPayment()],
                ['name' => 'Document Fee', 'amount' => config('discount.document_fee')]
            ]
        };
    }

    public function paymentCount(): int
    {
        return (int) Arr::get(explode('-', $this->input('type')), 1, 1);
    }

    public function validationData(): array
    {
        return [
            'type' => $this->getType(),
            'payment_count' => $this->paymentCount(),
        ];
    }

    private function getType(): string
    {
        return explode('-', $this->input('type'))[0];
    }

    private function getProperty(): ?Property
    {
        /** @var Property $property */
        $property = $this->route('apn');

        return $property;
    }
}
