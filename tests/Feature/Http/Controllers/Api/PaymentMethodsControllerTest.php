<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodsControllerTest extends TestCase
{
    /** test */
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


    /** test */
    public function user_can_view_payment_intent()
    {
        $id = 'pi_9YR1V7cpCF1oMk3oRKf3vuKF';

        $response = $this->get(
            '/api/payment-methods/payment-intents/' . $id,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_create_payment_intent()
    {
        $data = [
            'amount' => 200.00
        ];

        $response = $this->post(
            '/api/payment-methods/payment-intents',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_attach_payment_intent()
    {
        $data = [
            'payment_intent_id' => 'pi_eRKvZHvVduC1GFMz8DK1ERE9',
            'card_number' => '4343434343434345', 
            'exp_month' => '12', 
            'exp_year' => '25', 
            'cvc' => '251', 
            'name' => 'Gene Phillip Artista', 
            'phone_number' => '09154082715', 
            'email' => 'genephillip222@gmail.com',
            'request_type' => 'POST',
            'plan_type' => 'Basic'
        ];

        $response = $this->post(
            '/api/payment-methods/payment-intents/attach-payment-method',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_cancel_payment_intent()
    {
        $id = 'pi_9YR1V7cpCF1oMk3oRKf3vuKF';

        $response = $this->put(
            '/api/payment-methods/payment-intents/' . $id . '/cancel',
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
