<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodsControllerTest extends TestCase
{
    /** @test */
    public function user_can_create_e_payment()
    {
        $data = [
            'type' => 'gcash',
            'amount' => '10000'
        ];

        $response = $this->post(
            '/api/payment-methods/e-payment',
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));

        $this->assertResponse($response);
    }

    /** test */
    public function user_cannot_create_e_payment_on_empty_required_fields()
    {
        $data = [
            'type' => '',
            'amount' => ''
        ];

        $response = $this->post(
            '/api/payment-methods/e-payment',
            $data,
            $this->apiHeader()
        );

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'status_message'
            ]);
    }
}
