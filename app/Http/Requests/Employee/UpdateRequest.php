<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;
use App\Models\Employee;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->id;

        return [
            'avatar_path' => ['required', 'string'],
            'id' => ['required', 'integer', 'exists:employees'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', "unique:employees,email,$id"],
            'phone' => ['required', 'string', "unique:employees,phone,$id"],
            'pin_code' => ['required', 'string', 'min:4', 'max:4', "unique:employees,pin_code,$id"],
            'role_id' => ['required', 'integer', 'exists:roles,id']
        ];
    }
}
