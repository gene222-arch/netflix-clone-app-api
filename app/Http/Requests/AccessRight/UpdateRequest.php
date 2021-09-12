<?php

namespace App\Http\Requests\AccessRight;

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
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'role_name' => ['required', 'string', "unique:roles,name,{$this->role_id}"],
            'permissions.*' => ['required', 'integer', 'distinct', 'exists:permissions,id']
        ];
    }
}
