<?php

namespace App\Http\Requests\AccessRight;

use App\Http\Requests\BaseRequest;

class AssignRoleToUsersRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids.*' => ['required', 'integer', 'distinct', 'exists:users,id']
        ];
    }
}
