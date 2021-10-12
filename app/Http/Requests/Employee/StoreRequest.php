<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar_path' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users', 'unique:employees'],
            'phone' => ['required', 'string', 'unique:employees'],
            'pin_code' => ['required', 'string', 'min:4', 'max:4', 'unique:employees'],
            'role_id' => ['required', 'integer', 'exists:roles,id']
        ];
    }
}
