<?php

namespace App\Http\Requests\PaymentMethod;

use App\Http\Requests\BaseRequest;

class AttachPaymentIntentRequest extends BaseRequest
{
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
            'payment_intent_id' => ['required', 'string'],
            'card_number' => ['required', 'string', 'min:16'], 
            'exp_month' => ['required', 'string', 'min:2', 'max:2'], 
            'exp_year' => ['required', 'string', 'min:2', 'max:2'], 
            'cvc' => ['required', 'string', 'min:3', 'max:3'], 
            'name' => ['required', 'string'], 
            'phone_number' => ['required', 'string'], 
            'email' => ['required', 'email'],
            'request_type' => ['nullable', 'string', 'in:POST,PUT'],
            'plan_type' => ['required', 'string', 'in:Basic,Standard,Premium']
        ];
    }
}
