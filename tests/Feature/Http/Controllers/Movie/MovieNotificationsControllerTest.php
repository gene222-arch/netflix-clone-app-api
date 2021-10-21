<?php

namespace Tests\Feature\Http\Controllers\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieNotificationsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_movie_notifications()
    {
        $response = $this->get(
            '/api/movie-notifications',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
