<?php

namespace App\Http\Requests\PaymentMethod;

use App\Http\Requests\BaseRequest;

class EPaymentRequest extends BaseRequest
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
            'type' => ['required', 'string', 'in:gcash,grab_pay'],
            'amount' => ['required', 'numeric', 'min:100'],
            'currency' => ['nullable', 'string', 'in:PHP'],
            'email' => ['required', 'email'],
            'send_payment_authorization_notif' => ['nullable', 'boolean'],
            'request_type' => ['nullable', 'string', 'in:POST,PUT']
        ];
    }
}
