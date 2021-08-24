<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyDownloadsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_downloaded_movies()
    {
        $response = $this->get(
            '/api/my-downloads',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_downloaded_movie()
    {
        $id = 1;

        $response = $this->get(
            "/api/my-downloads/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_download_a_movie()
    {
        $data = [
            'user_profile_id' => 1,
            'movie_id' => 15,
            'uri' => 'downloaduri'
        ];

        $response = $this->post(
            '/api/my-downloads',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_delete_a_downloaded_movie()
    {
        $userProfileId = 2;

        $data = [
            'ids' => [
                1
            ]
        ];

        $response = $this->delete(
            "/api/my-downloads/user-profiles/$userProfileId",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
