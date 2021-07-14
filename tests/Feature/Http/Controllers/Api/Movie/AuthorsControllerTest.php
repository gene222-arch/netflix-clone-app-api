<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_authors()
    {
        $response = $this->get(
            '/api/authors',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_author()
    {
        $id = 1;

        $response = $this->get(
            "/api/authors/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_author()
    {
        $data = [
            'pseudonym' => '',
            'birth_name' => 'Makoto Shinkai',
            'gender' => 'Male',
            'height_in_cm' => '180.34',
            'biographical_information' => "Makoto Niitsu, also known as Makoto Shinkai, is a Japanese animator, filmmaker and manga artist best known for directing Your Name, the third highest-grossing anime film of all time and 2019's Weathering",
            'birth_details' => '',
            'date_of_birth' => '1973-02-09',
            'place_of_birth' => '',
            'death_details' => '',
            'date_of_death' => '',
            'enabled' => false,
        ];

        $response = $this->post(
            '/api/authors',
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));
        
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_author()
    {
        $id = 1;

        $data = [
            'birth_name' => 'Makoto Shinkai',
            'gender' => 'Male',
            'height_in_cm' => '180.34',
            'biographical_information' => "Makoto Niitsu, also known as Makoto Shinkai, is a Japanese animator, filmmaker and manga artist best known for directing Your Name, the third highest-grossing anime film of all time and 2019's Weathering",
            'birth_details' => '',
            'date_of_birth' => '1973-02-09',
            'place_of_birth' => '',
            'death_details' => '',
            'date_of_death' => '',
            'enabled' => false,
        ];

        $response = $this->put(
            "/api/authors/$id",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_authors()
    {
        $data = [
            'ids' => [2]
        ];

        $response = $this->delete(
            '/api/authors',
            $data
        );

        $this->assertResponse($response);
    }

}
