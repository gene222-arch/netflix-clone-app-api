<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRatingsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_user_ratings()
    {
        $response = $this->get(
            '/api/user-ratings',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_user_rating()
    {
        $id = 1;

        $response = $this->get(
            "/api/user-ratings/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_view_user_rating_by_user_id()
    {
        $response = $this->get(
            "/api/user-ratings/users",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_view_user_rating_by_user_profile_id()
    {
        $id = 1;

        $response = $this->get(
            "/api/user-ratings/user-profiles/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_view_user_rating_by_movie_id()
    {
        $id = 1;

        $response = $this->get(
            "/api/user-ratings/movies/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_user_rating()
    {
        $data = [
            'movie_id' => 1,
            'user_profile_id' => 1,
            'rate' => 'dislike'
        ];

        $response = $this->post(
            '/api/user-ratings',
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));
        $this->assertResponse($response);
    }

    /** test */
    public function user_can_delete_user_rating()
    {
        $data = [
            'movie_id' => 1,
            'user_profile_id' => 2
        ];

        $response = $this->delete(
            "/api/user-ratings",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
