<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\ExtendedFormRequest;

class ProductGroupRequest extends ExtendedFormRequest
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
                Rule::unique('product_groups', 'label')
                    ->when($this->product_group, function ($rule) {
                        return $rule->ignore($this->product_group);
                    })
            ],
            'property_ids' => [
                'required',
                'array',
            ],
            'property_ids.*' => [
                'exists:properties,id',
            ]
        ];
    }
}
