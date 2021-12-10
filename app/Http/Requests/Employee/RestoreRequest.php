<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;

class RestoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids.*' => ['required', 'distinct', 'integer', 'exists:employees,id']
        ];
    }
}
