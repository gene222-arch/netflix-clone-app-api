<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;

class LoginByPinRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pin_code' => ['required', 'string']
        ];
    }
}
