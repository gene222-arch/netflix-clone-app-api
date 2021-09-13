<?php

namespace App\Http\Requests\UserProfile;

use App\Http\Requests\BaseRequest;

class ManagePinCodeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
            'pin_code' => ['required', 'string', 'max:4', 'unique:user_profiles,pin_code']
        ];
    }
}
