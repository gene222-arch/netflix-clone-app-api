<?php

namespace App\Http\Requests\UserProfile;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

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
            'name' => ['required', 'string', 'unique:user_profiles'],
            'avatar' => ['required', 'string'],
            'is_for_kids' => ['required', 'boolean']
        ];
    }
}
