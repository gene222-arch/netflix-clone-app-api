<?php

namespace App\Services;

use Illuminate\Support\Str;
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

    public function attachPaymentIntent(
        string $paymentIntentId,
        string $cardNumber, 
        int $expMonth, 
        int $expYear, 
        string $cvc, 
        string $name, 
        string $phoneNumber, 
        string $email,
        string $requestType,
        string $planType
    )
    {
        $subscriptionPath = $requestType === 'POST' ? 'subscribed-successfully' : 'updated-successfully';
        $address = auth('api')->user()?->address;

        $paymentMethod = Paymongo::paymentMethod()->create([
            'type' => 'card',
            'details' => [
                'card_number' => $cardNumber,
                'exp_month' => $expMonth,
                'exp_year' => $expYear,
                'cvc' => $cvc,
            ],
            'billing' => [
                'address' => [
                    'line1' => $address?->city_name . ',' . $address?->country,
                    'city' => $address?->city_name,
                    'state' => $address?->city_name,
                    'country' => Str::substr($address?->country_code, 0, 2),
                    'postal_code' => $address?->zip_code,
                ],
                'name' => $name,
                'email' => $email,
                'phone' => $phoneNumber
            ],
        ]);

        $paymentIntent = Paymongo::paymentIntent()->find($paymentIntentId);
        $paymentIntent_ = collect($paymentIntent)->first();

        // ? Should i add the client key?
        // $paymentIntent_['client_key'] 
        $paymentIntent->attach(
            $paymentMethod->id, 
            env('REACT_APP_URL') . "/subscriptions/$subscriptionPath?email=$email&type=$planType&paymentMethod=Card"
        );

        return collect($paymentIntent)->first();
    }

    public static function ePayment(
        string $type, // Gcash or Grab Pay
        float $amount, 
        string $currency,
        string $email,
        string $requestType,
        string $paymentMethod,
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
                'success' => env('REACT_APP_URL') . "/subscriptions/$subscriptionPath?email=$email&type=$planType&paymentMethod=$paymentMethod",
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