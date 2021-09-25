<?php

namespace App\Http\Requests\ActivityLog;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url_view_path' => ['nullable', 'string', 'url'],
            'description' => ['nullable', 'string', 'min:12']
        ];
    }
}
