<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyListsControllerTest extends TestCase
{

    /** test */
    public function user_can_toggle_my_list()
    {
        $data = [
            'user_profile_id' => 3,
            'movie_id' => 1
        ];

        $response = $this->post(
            '/api/my-lists',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
