<?php

namespace App\Http\Requests;

use App\Models\PropertyInquiry;
use App\UserTracker;
use Illuminate\Foundation\Http\FormRequest;

class StorePropertyInquiry extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'buy_reasons' => ['required'],
            'question' => ['required', 'string'],
            'spanish' => ['required', 'boolean'],
            'contact_allowed' => ['required', 'boolean'],
        ];
    }

    public function inquiry(): PropertyInquiry
    {
        /** @var PropertyInquiry */
        return PropertyInquiry::query()->create(
            array_merge(
                $this->validated(),
                [
                    'property_id' => $this->route('property')->getKey(),
                    'tracking' => [
                        'params' => UserTracker::getParams(),
                        'original_landing_page' => UserTracker::getFirstPage(),
                        'user_agent' => UserTracker::getUserAgent(),
                        'page' => $this->input('page'),
                    ],
                ]
            )
        );
    }
}
