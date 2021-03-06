<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecentlyWatchedMoviesControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_recently_watched_movies()
    {
        $response = $this->get(
            '/api/recently-watched-movies',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_recently_watched_movie()
    {
        $id = 1;

        $response = $this->get(
            "/api/recently-watched-movies/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_recently_watched_movie()
    {
        $userProfileId = 1;

        $data = [
            'movie_id' => 23,
            'duration_in_millis' => 1231231
        ];

        $response = $this->post(
            '/api/recently-watched-movies/user-profiles/' . $userProfileId,
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_recently_watched_movie()
    {
        $data = [
            'user_profile_id',
            'movie_id'
        ];

        $response = $this->put(
            '/api/recently-watched-movies',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_recently_watched_movie_position_millis()
    {
        $data = [
            'user_profile_id' => 4,
            'movie_id' => 5,
            'last_played_position_millis' => 1231
        ];

        $response = $this->put(
            '/api/recently-watched-movies/position-millis',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_delete_recently_watched_movie()
    {
        $data = [
            'user_profile_id',
            'movie_id'
        ];

        $response = $this->delete(
            "/api/recently-watched-movies",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
