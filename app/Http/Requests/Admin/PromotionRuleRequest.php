<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Models\Admin\PromotionRule;
use App\Http\Requests\Admin\ExtendedFormRequest;

class PromotionRuleRequest extends ExtendedFormRequest
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
                    PromotionRule::TYPE_COUPON,
                    PromotionRule::TYPE_PRODUCT_IN_GROUP,
                ]),
            ],
            'config' => [
                'required',
                'array',
            ],
            'config.coupon_code' => [
                'required_if:type,coupon',
                'string',
                'unique:promotion_rules,config->coupon_code'
            ],
            'config.product_group_ids' => [
                'required_if:type,product_in_group',
                'exists:product_groups,id',
                'array'
            ],
        ];
    }
}
