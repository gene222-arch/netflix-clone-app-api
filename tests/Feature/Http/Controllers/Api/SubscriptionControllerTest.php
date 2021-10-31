<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
{

    /** test */
    public function subscriber_can_view_any_subscriptions()
    {
        $response = $this->get(
            '/api/subscriptions',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function subscriber_can_view_subscription()
    {
        $id = 2;

        $response = $this->get(
            "/api/subscriptions/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function subscriber_can_create_subscription()
    {
        $data = [
            'type' => 'Premium'
        ];

        $response = $this->post(
            '/api/subscriptions',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function subscriber_can_cancel_subscription()
    {
        $data = [];

        $response = $this->put(
            '/api/subscriptions/cancel',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function subscriber_can_delete_subscriptions()
    {
        $data = [
            'ids' => [1]
        ];

        $response = $this->delete(
            "/api/subscriptions",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
