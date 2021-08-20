<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VerificationControllerTest extends TestCase
{

    /** test */
    public function can_verify_email_address()
    {
        $id = 1;
        $hash = 'hash';

        $response = $this->post(
            "/api/verify-email/${id}/${hash}",
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_resend_email_verification()
    {
        $response = $this->post(
            '/api/resend',
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
