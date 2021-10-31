<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethod\EPaymentRequest;
use App\Services\PaymongoService;

class PaymentMethodsController extends Controller
{
    public function ePayment(EPaymentRequest $request, PaymongoService $service)
    {    
        $source = $service->ePayment(
            $request->type,
            $request->amount,
            $request->input('currency', 'PHP'),
            $request->email,
            $request->input('send_payment_authorization_notif', false)
        );

        return $this->success($source, 'E Payment source created successfully.');
    }
}
