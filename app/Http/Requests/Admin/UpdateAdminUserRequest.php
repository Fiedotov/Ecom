<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\AdminUser;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\ExtendedFormRequest;

class UpdateAdminUserRequest extends ExtendedFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'first_name' => [
                'string',
                'sometimes',
            ],
            'last_name' => [
                'string',
                'sometimes',
            ],
            'email' => [
                'email',
                'sometimes',
                Rule::unique('admin_users', 'email')
                    ->when($this->admin_user, function ($rule) {
                        return $rule->ignore($this->admin_user);
                    })
            ],
            'status' => [
                Rule::in([
                    AdminUser::ACTIVE,
                    AdminUser::INACTIVE
                ])
            ],
        ];

        return $rules;
    }
}
