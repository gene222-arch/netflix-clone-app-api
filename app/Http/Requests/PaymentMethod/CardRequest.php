<?php

namespace App\Http\Requests\PaymentMethod;

use App\Http\Requests\BaseRequest;

class CardRequest extends BaseRequest
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
            'amount' => ['required', 'numeric', 'min:200']
        ];
    }
}
