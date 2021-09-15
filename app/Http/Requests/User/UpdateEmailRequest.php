<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdateEmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'unique:users,email,' . request()->user('api')->id ]
        ];
    }
}
