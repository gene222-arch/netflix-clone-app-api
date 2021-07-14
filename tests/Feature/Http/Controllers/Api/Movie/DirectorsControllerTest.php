<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DirectorsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_directors()
    {
        $response = $this->get(
            '/api/directors',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_director()
    {
        $id = 1;

        $response = $this->get(
            "/api/directors/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_director()
    {
        $data = [
            'pseudonym' => '',
            'birth_name' => 'Mone Kamishiraishi',
            'gender' => 'Female',
            'height_in_cm' => '152',
            'biographical_information' => 'Mone Kamishiraishi was born on January 27, 1998 in Kagoshima, Japan. She is an actress, known for Your Name. (2016), Lady Maiko (2014) and Wolf Children (2012).',
            'birth_details' => '',
            'date_of_birth' => '1998-01-27',
            'place_of_birth' => '',
            'death_details' => '',
            'date_of_death' => '',
            'enabled' => false,
        ];

        $response = $this->post(
            '/api/directors',
            $data,
            $this->apiHeader()
        );
        
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_director()
    {
        $id = 1;

        $data = [
            'birth_name' => 'Mone Kamishiraishi',
            'gender' => 'Female',
            'height_in_cm' => '152',
            'biographical_information' => 'Mone Kamishiraishi was born on January 27, 1998 in Kagoshima, Japan. She is an actress, known for Your Name. (2016), Lady Maiko (2014) and Wolf Children (2012).',
            'birth_details' => '',
            'date_of_birth' => '1998-01-27',
            'place_of_birth' => '',
            'death_details' => '',
            'date_of_death' => '',
            'enabled' => false,
        ];

        $response = $this->put(
            "/api/directors/$id",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_directors()
    {
        $data = [
            'ids' => [2]
        ];

        $response = $this->delete(
            '/api/directors',
            $data
        );

        $this->assertResponse($response);
    }

}
