<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'allow_access_to_location' => ['required', 'boolean'],
            'avatar_path' => ['required', 'string'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'plan_type' => ['required', 'string', 'in:Basic,Standard,Premium'],
            'check_out_url' => ['required', 'url'],
            'payment_method' => ['required', 'string', 'in:Card,Gcash,Grab Pay']
        ];
    }
}
