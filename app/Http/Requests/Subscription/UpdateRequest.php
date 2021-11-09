<?php

namespace App\Http\Requests\Subscription;

use App\Http\Requests\BaseRequest;

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
            'type' => ['required', 'string', 'in:Basic,Standard,Premium'],
            'user_email' => ['required', 'email', 'string', 'exists:users,email', new \App\Rules\Subscribed()],
            'payment_method' => ['required', 'string', 'in:Card,Gcash,Grab Pay']
        ];
    }
}
