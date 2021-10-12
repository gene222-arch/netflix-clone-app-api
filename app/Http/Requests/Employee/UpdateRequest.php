<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'id' => ['required', 'integer', 'exists:employees'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users', "unique:employees,email,{$this->id}"],
            'phone' => ['required', 'string', "unique:employees,phone,{$this->id}"],
            'pin_code' => ['required', 'string', 'min:4', 'max:4', "unique:employees,pin_code,{$this->id}"]
        ];
    }
}
