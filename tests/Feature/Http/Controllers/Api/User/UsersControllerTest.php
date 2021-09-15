<?php

namespace Tests\Feature\Http\Controllers\Api\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    /** test */
    public function user_with_right_access_can_view_any_users()
    {
        $response = $this->get(
            '/api/users',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_update_email()
    {
        $data = [
            'email' => 'minorkayls@gmail.com'
        ];

        $response = $this->put(
            '/api/users',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
