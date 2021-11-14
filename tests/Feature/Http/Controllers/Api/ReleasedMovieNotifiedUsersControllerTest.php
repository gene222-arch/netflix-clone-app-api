<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReleasedMovieNotifiedUsersControllerTest extends TestCase
{
    /** test */
    public function user_can_create_a_notified_user()
    {
        $id = 17;

        $response = $this->post(
            '/api/released-movie-notified-users/' . $id,
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
