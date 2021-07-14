<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{

    /** @test */
    public function user_can_register()
    {
        $data = [
            'first_name' => 'Bryan',
            'last_name' => 'Paz',
            'email' => 'bryan.p@gmail.com',
            'password' => 'bryan.p@gmail.com',
            'password_confirmation' => 'bryan.p@gmail.com'
        ];

        $response = $this->post(
            '/api/auth/register', 
            $data, 
            $this->apiHeader());
            
        $this->assertResponse($response);
    }
}