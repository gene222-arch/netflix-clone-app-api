<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenresControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_genres()
    {
        $response = $this->get(
            '/api/genres/',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_genre()
    {
        $id = 1;

        $response = $this->get(
            "/api/genres/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_genre()
    {
        $data = [
            'name' => 'Horrors',
            'enabled' => false
        ];

        $response = $this->post(
            '/api/genres/',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_genre()
    {
        $id = 1;

        $data = [
            'id' => $id,
            'name' => 'Horror',
            'enabled' => false
        ];

        $response = $this->put(
            "/api/genres/$id",
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_genres()
    {
        $data = [
            'ids' => [2]
        ];

        $response = $this->delete(
            '/api/genres',
            $data
        );

        $this->assertResponse($response);
    }

}
