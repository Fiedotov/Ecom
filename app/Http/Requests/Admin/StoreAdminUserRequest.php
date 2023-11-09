<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\AdminUser;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\ExtendedFormRequest;

class StoreAdminUserRequest extends ExtendedFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
                'required',
            ],
            'last_name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'email',
                'unique:admin_users'
            ],
            'status' => [
                Rule::in([
                    AdminUser::ACTIVE,
                    AdminUser::INACTIVE
                ])
            ],
            'password' => [
                'required',
            ]
        ];

        return $rules;
    }
}
