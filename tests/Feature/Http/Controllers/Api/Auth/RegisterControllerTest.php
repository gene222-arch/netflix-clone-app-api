<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{

    /** test */
    public function user_can_register()
    {
        $data = [
            'first_name' => 'Bryan',
            'last_name' => 'Paz',
            'email' => 'bryan.pgmail.com',
            'password' => 'bryan.pgmail.com',
            'password_confirmation' => 'bryan.pgmail.com',
            'allow_access_to_location' => true
        ];

        $response = $this->post(
            '/api/auth/register', 
            $data, 
            $this->apiHeader());
            
        $this->assertResponse($response);
    }
}
