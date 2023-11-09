<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Promotion;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\ExtendedFormRequest;

class PromotionRequest extends ExtendedFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'label' => [
                Rule::requiredIf($this->isMethod('POST')),
                'string',
                Rule::unique('promotions', 'label')
                    ->when($this->promotion, function ($rule) {
                        return $rule->ignore($this->promotion);
                    }),
            ],
            'date_to' => [
                'nullable',
                'date',
                'date_format:Y-m-d H:i:s',
            ],
            'date_from' => [
                'nullable',
                'date',
                'date_format:Y-m-d H:i:s',
            ],
            'rules_match' => [
                Rule::requiredIf($this->isMethod('POST')),
                Rule::in([
                    Promotion::RULES_MATCH_OR,
                    Promotion::RULES_MATCH_AND,
                ]),
            ],
            'rule_ids' => [
                'required',
                'array',
            ],
            'rule_ids.*' => [
                'exists:promotion_rules,id',
            ],
            'reward_ids' => [
                'required',
                'array',
            ],
            'reward_ids.*' => [
                'exists:promotion_rewards,id',
            ],
        ];
    }
}
