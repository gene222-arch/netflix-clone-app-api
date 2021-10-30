<?php

namespace App\Services;

use Luigel\Paymongo\Facades\Paymongo;

class PaymongoService
{
    private static string $successPaymentUrl;
    private static string $failedPaymentUrl;

    public function __construct()
    {
        self::$successPaymentUrl = env('REACT_APP_URL') . '/authorized?status=successfull';
        self::$failedPaymentUrl = env('REACT_APP_URL') . '/unauthorized?status=failed';
    }

    public static function find(string $id)
    {
        return Paymongo::paymentMethod()->find($id) ?? NULL;
    }

    public static function card(array $details, string $name, string $email, string $phone)
    {
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

        $payment = Paymongo::paymentMethod()->create($payload);

        return $payment;
    }

    public static function ePayment(
        string $type, // Gcash or Grab Pay
        float $amount, 
        string $currency
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
        ];

        $source = Paymongo::source()->create($payload);

        return collect($source)->first();
    }
}