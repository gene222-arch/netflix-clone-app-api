<?php

namespace App\Services;

use Luigel\Paymongo\Facades\Paymongo;

class PaymongoService
{
    public static function find(string $id)
    {
        return Paymongo::paymentMethod()->find($id) ?? NULL;
    }

    public static function cardPaymentIntent(float $amount)
    {
        $paymentIntent = Paymongo::paymentIntent()->create([
            'amount' => $amount,
            'payment_method_allowed' => [
                'card'
            ],
            'payment_method_options' => [
                'card' => [
                    'request_three_d_secure' => 'automatic'
                ]
            ],
            'description' => 'Payment Intent',
            'statement_descriptor' => env('APP_NAME') . ' ' . 'Organization',
            'currency' => 'PHP'
        ]);

        return collect($paymentIntent)->first();
    }

    public static function ePayment(
        string $type, // Gcash or Grab Pay
        float $amount, 
        string $currency,
        string $email,
        string $requestType,
        bool $sendPaymentAuthorizationNotif = false,
    )
    {
        $planType = match($amount) {
            100.00 => 'Basic',
            200.00 => 'Standard',
            600.00 => 'Premium'
        };

        $subscriptionPath = $requestType === 'POST' ? 'subscribed-successfully' : 'updated-successfully';

        $payload = [
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'redirect' => [
                'success' => env('REACT_APP_URL') . "/subscriptions/$subscriptionPath?email=$email&type=$planType",
                'failed' => env('REACT_APP_URL') . '/subscriptions/unauthorized?status=failed'
            ],
        ];

        $source = Paymongo::source()->create($payload);
        $source = collect($source)->first();

        if ($sendPaymentAuthorizationNotif) {
            auth('api')
                ->user()
                ->sendPaymentAuthorizationNotification($source['redirect']['checkout_url']);
        }

        return $source;
    }
}