<?php

namespace App\Http\Requests\UserProfile;

use App\Http\Requests\BaseRequest;

class DisableRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids.*' => ['required', 'integer', 'exists:user_profiles,id']
        ];
    }
}
