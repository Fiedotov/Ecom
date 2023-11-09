<?php

namespace App\Http\Requests;

use App\Models\PaymentSubmission;
use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class SubmitTransaction extends FormRequest
{
    protected ?PaymentSubmission $submission;

    public function rules(): array
    {
        return [
            'customer.first_name' => ['required', 'string'],
            'customer.last_name' => ['required', 'string'],
            'customer.address' => ['required', 'string'],
            'customer.city' => ['required', 'string'],
            'customer.state' => ['required', 'string'],
            'customer.postal_code' => ['required', 'string'],
            'customer.phone' => ['required', 'string'],
            'customer.email' => ['required', 'email'],
            'customer.full_legal_name' => ['required', 'string'],
            'customer.referrer_name' => ['nullable', 'string'],
            'payments' => ['required', 'int'],
            'property_status' => [Rule::in([Property::STATUS_AVAILABLE])],
            'dataDescriptor' => ['required', 'string'],
            'dataValue' => ['required', 'string'],
        ];
    }

    public function property(): Property
    {
        return $this->route('apn');
    }

    public function validationData(): array
    {
        return array_merge(
            parent::validationData(),
            ['property_status' => $this->property()->status]
        );
    }

    public function messages(): array
    {
        return [
            'property_status.in' => 'Property is not available for purchase.',
        ];
    }

    public function paymentSubmission(): PaymentSubmission
    {
        /** @var PaymentSubmission $submission */
        $submission = PaymentSubmission::query()->create([
            'property_id' => $this->property()->id,
            'payload' => $this->payload(),
        ]);
        return $submission;
    }

    private function payload(): array
    {
        $payload = $this->all();

        Arr::set(
            $payload,
            'customer.referrer_name',
            Arr::get($payload, 'customer.referrer_name') ?? ''
        );

        return $payload;
    }
}
