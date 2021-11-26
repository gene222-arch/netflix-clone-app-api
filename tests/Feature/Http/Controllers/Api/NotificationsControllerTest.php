<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsControllerTest extends TestCase
{
    /** ztest */
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

    /** test */
    public function user_can_mark_all_as_read_payment_authorization_notifications()
    {
        $data = [];
        $response = $this->put(
            "/api/notifications/payment-authorizations/mark-all-as-read",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_mark_as_read_payment_authorization_notifications()
    {
        $id = 1;

        $data = [];
        $response = $this->put(
            "/api/notifications/payment-authorizations/mark-as-read/$id",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_clear_payment_authorization_notifications()
    {
        $data = [];
        $response = $this->delete(
            "/api/notifications/payment-authorizations/clear",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);   
    }
}
