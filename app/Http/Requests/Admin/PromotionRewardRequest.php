<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\ExtendedFormRequest;
use App\Models\Admin\PromotionReward;

class PromotionRewardRequest extends ExtendedFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => [
                'required',
                Rule::in([
                    PromotionReward::PRODUCT_DISCOUNT,
                    PromotionReward::DEPOSIT_DISCOUNT,
                ]),
            ],
            'config' => [
                'required',
                'array',
            ],
            'config.discount_strategy' => [
                'required',
                'string',
                'in:percentage,fixed'
            ],
            'config.discount_amount' => [
                'required',
                'numeric'
            ],
            'config.apply_to_monthly' => [
                'required',
                'boolean'
            ],
            'config.apply_to_full_price' => [
                'required',
                'boolean'
            ]
        ];
    }
}
