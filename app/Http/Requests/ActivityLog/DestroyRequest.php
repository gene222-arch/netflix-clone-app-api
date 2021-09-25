<?php

namespace App\Http\Requests\ActivityLog;

use App\Http\Requests\BaseRequest;

class DestroyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids.*' => ['required', 'distinct', 'integer', 'exists:activity_logs,id']
        ];
    }
}
