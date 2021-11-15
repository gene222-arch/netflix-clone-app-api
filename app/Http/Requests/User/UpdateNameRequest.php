<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdateNameRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string']
        ];
    }
}
