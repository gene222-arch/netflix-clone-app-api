<?php

namespace App\Services;

use Luigel\Paymongo\Facades\Paymongo;

class PaymongoService
{
    private string $successPaymentUrl;
    private string $failedPaymentUrl;

    public function __construct()
    {
        self::$successPaymentUrl = env('REACT_APP_URL') . '/success-payment';
        self::$failedPaymentUrl = env('REACT_APP_URL') . '/failed-payment';
    }

    public static function find(string $id)
    {
        return Paymongo::paymentMethod()->find($id) ?? NULL;
    }

    public static function card(array $details, array $billingAddress, string $name, string $email, string $phone)
    {
        $payment = Paymongo::paymentMethod()
            ->create([
                'type' => 'card',
                'details' => [
                    'card_number' => $details['cardNumber'],
                    'exp_month' => $details['expMonth'],
                    'exp_year' => $details['expYear'],
                    'cvc' => $details['cvc'],
                ],
                'billing' => [
                    'address' => [
                        'line1' => $billingAddress['line1'],
                        'line2' => $billingAddress['line2'] ?? NULL,
                        'city' => $billingAddress['city'],
                        'state' => $billingAddress['state'],
                        'country' => $billingAddress['country'], // Country ISO Code
                        'postal_code' => $billingAddress['postalCode'],
                    ]
                ],
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ]);

        return $payment;
    }

    public static function ePayment(
        string $type, // Gcash or Grab Pay
        float $amount, 
        array $billingAddress, 
        string $name, 
        string $email, 
        string $phone, 
        string $currency = 'PHP'
    )
    {
        $payload = [
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'redirect' => [
                'success' => self::$successPaymentUrl,
                'failed' => self::$failedPaymentUrl
            ],
            'billing' => [
                'address' => [
                    'line1' => $billingAddress['line1'],
                    'line2' => $billingAddress['line2'] ?? NULL,
                    'city' => $billingAddress['city'],
                    'state' => $billingAddress['state'],
                    'country' => $billingAddress['country'], // Country ISO Code
                    'postal_code' => $billingAddress['postalCode'],
                ]
            ],
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];

        return Paymongo::source()->create($payload);
    }
}