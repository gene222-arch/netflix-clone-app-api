<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    /** test */
    public function user_can_view_user_info()
    {
        $id = 1;

        $response = $this->get(
            "/api/auth/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_check_if_password_match()
    {
        $data = [
            'password' => '123123123123'
        ];

        $response = $this->post(
            "/api/auth/check-password",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
