<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemindMesControllerTest extends TestCase
{
    /** test */
    public function user_can_create_remind_me()
    {
        $data = [
            'user_profile_id' => 3,
            'coming_soon_movie_id' => 1
        ];

        $response = $this->post(
            '/api/remind-mes',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_delete_remind_me()
    {
        $data = [
            'user_profile_id' => 1,
            'coming_soon_movie_id' => 1
        ];

        $response = $this->delete(
            '/api/remind-mes',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
