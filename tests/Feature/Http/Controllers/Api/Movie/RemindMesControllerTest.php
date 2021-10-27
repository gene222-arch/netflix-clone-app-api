<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemindMesControllerTest extends TestCase
{
    /** test */
    public function user_can_toggle_remind_me()
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

    /** test */
    public function user_can_mark_as_read()
    {
        $data = [
            'user_profile_id' => 4,
            'coming_soon_movie_id' => 6
        ];

        $response = $this->put(
            '/api/remind-mes/mark-as-read',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


}
