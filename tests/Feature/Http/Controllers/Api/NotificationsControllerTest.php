<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsControllerTest extends TestCase
{
    /** @test */
    public function user_can_view_payment_authorizations()
    {
        $response = $this->get(
            "/api/notifications/payment-authorizations",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_view_payment_authorization_by_user_id()
    {
        $response = $this->get(
            "/api/notifications/payment-authorizations/current",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
