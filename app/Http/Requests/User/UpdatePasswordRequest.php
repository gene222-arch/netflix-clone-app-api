<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdatePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => ['required', 'string', 'min:8', 'current_password:api'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ];
    }

    public function messages()
    {
        return [
            'current_password.current_password' => 'Current password is incorrect'
        ];
    }
}
