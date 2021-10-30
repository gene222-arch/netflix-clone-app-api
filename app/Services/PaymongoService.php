<?php

namespace App\Services;

use Luigel\Paymongo\Facades\Paymongo;

class PaymongoService
{
    private string $successPaymentUrl;
    private string $failedPaymentUrl;

    public function __construct()
    {
        self::$successPaymentUrl = env('REACT_APP_URL') . '/authorized?status=successfull';
        self::$failedPaymentUrl = env('REACT_APP_URL') . '/unauthorized?status=failed';
    }

    public static function find(string $id)
    {
        return Paymongo::paymentMethod()->find($id) ?? NULL;
    }

    public static function card(array $details, array $billingAddress = [], string $name, string $email, string $phone)
    {
        $billingAddressCollection = collect($billingAddress);

        $payload = [
            'type' => 'card',
            'details' => [
                'card_number' => $details['cardNumber'],
                'exp_month' => $details['expMonth'],
                'exp_year' => $details['expYear'],
                'cvc' => $details['cvc'],
            ],
            'billing' => NULL,  
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];

        if ($billingAddressCollection->count()) 
        {
            $payload = $payload + [
                'billing' => [
                    'address' => [
                        'line1' => $billingAddress['line1'],
                        'line2' => $billingAddress['line2'] ?? NULL,
                        'city' => $billingAddress['city'],
                        'state' => $billingAddress['state'],
                        'country' => $billingAddress['country'], // Country ISO Code
                        'postal_code' => $billingAddress['postalCode'],
                    ]
                ]
            ];
        }

        $payment = Paymongo::paymentMethod()->create($payload);

        return $payment;
    }

    public static function ePayment(
        string $type, // Gcash or Grab Pay
        float $amount, 
        array $billingAddress = [], 
        string $name, 
        string $email, 
        string $phone, 
        string $currency = 'PHP'
    )
    {
        $billingAddressCollection = collect($billingAddress);

        $payload = [
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'redirect' => [
                'success' => self::$successPaymentUrl,
                'failed' => self::$failedPaymentUrl
            ],
            'billing' => NULL,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];

        if ($billingAddressCollection->count()) 
        {
            $payload = $payload + [
                'billing' => [
                    'address' => [
                        'line1' => $billingAddress['line1'],
                        'line2' => $billingAddress['line2'] ?? NULL,
                        'city' => $billingAddress['city'],
                        'state' => $billingAddress['state'],
                        'country' => $billingAddress['country'], // Country ISO Code
                        'postal_code' => $billingAddress['postalCode'],
                    ]
                ]
            ];
        }

        return Paymongo::source()->create($payload);
    }
}