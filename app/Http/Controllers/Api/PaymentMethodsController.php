<?php

namespace App\Http\Controllers\Api;

use App\Services\PaymongoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethod\CardRequest;
use App\Http\Requests\PaymentMethod\EPaymentRequest;
use App\Http\Requests\PaymentMethod\AttachPaymentIntentRequest;
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

    public function attachPaymentIntent(AttachPaymentIntentRequest $request, PaymongoService $service)
    {
        $paymentIntent = $service->attachPaymentIntent(
            $request->payment_intent_id,
            $request->card_number, 
            $request->exp_month, 
            $request->exp_year, 
            $request->cvc, 
            $request->name, 
            $request->phone_number, 
            $request->email,
            $request->input('request_type', 'POST'),
            $request->plan_type
        );

        return $this->success($paymentIntent, 'Payment Intent attached successfully to card.');
    }

    public function showPaymentIntent(string $paymentIntentId)
    {
        $paymentIntent = Paymongo::paymentIntent()->find($paymentIntentId);
        $paymentIntent = collect($paymentIntent)->first();

        return $this->success($paymentIntent);
    }

    public function cancelPaymentIntent(string $paymentIntentId)
    {
        Paymongo::paymentIntent()
            ->find($paymentIntentId)
            ->cancel();

        return $this->success(NULL, 'Payment Intent cancelled successfully');
    }
}
