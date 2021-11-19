<?php

namespace App\Http\Requests\UserProfile;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:user_profiles'],
            'name' => ['required', 'string', 'min:4', 'max:8', "unique:user_profiles,name,{$this->id}"],
            'avatar' => ['required', 'string'],
            'is_for_kids' => ['required', 'boolean']
        ];
    }
}
