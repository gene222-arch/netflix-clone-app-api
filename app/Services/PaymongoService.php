<?php

namespace App\Services;

use Luigel\Paymongo\Facades\Paymongo;

class PaymongoService
{
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
        string $currency,
        string $email
    )
    {
        $payload = [
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'redirect' => [
                'success' => env('REACT_APP_URL') . '/auth/subscribed-successfully?email=' . $email,
                'failed' => env('REACT_APP_URL') . '/unauthorized?status=failed'
            ],
        ];

        $source = Paymongo::source()->create($payload);

        return collect($source)->first();
    }
}