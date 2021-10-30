<?php

namespace App\Http\Requests\PaymentMethod;

use App\Http\Requests\BaseRequest;

class EPaymentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', 'string', 'in:gcash,grab_pay'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'currency' => ['nullable', 'string', 'in:PHP']
        ];
    }
}
