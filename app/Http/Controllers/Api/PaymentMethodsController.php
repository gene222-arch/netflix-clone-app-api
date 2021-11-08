<?php

namespace App\Http\Controllers\Api;

use App\Services\PaymongoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethod\CardRequest;
use App\Http\Requests\PaymentMethod\EPaymentRequest;
use Luigel\Paymongo\Facades\Paymongo;

class PaymentMethodsController extends Controller
{
    public function ePayment(EPaymentRequest $request, PaymongoService $service)
    {    
        $source = $service->ePayment(
            $request->type,
            $request->amount,
            $request->input('currency', 'PHP'),
            $request->email,
            $request->input('request_type', 'POST'),
            $request->input('send_payment_authorization_notif', false)
        );

        return $this->success($source, 'E Payment source created successfully.');
    }

    public function storePaymentIntent(CardRequest $request, PaymongoService $service)
    {
        $paymentIntent = $service->cardPaymentIntent($request->amount);

        return $this->success($paymentIntent, 'Payment Intent Created');
    }

    public function showPaymentIntent(string $paymentIntentId)
    {
        return $this->success(Paymongo::paymentIntent()->find($paymentIntentId));
    }

    public function cancelPaymentIntent(string $paymentIntentId)
    {
        Paymongo::paymentIntent()
            ->find($paymentIntentId)
            ->cancel();

        return $this->success(NULL, 'Payment Intent cancelled successfully');
    }
}
