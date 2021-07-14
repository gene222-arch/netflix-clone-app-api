<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CastsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_casts()
    {
        $response = $this->get(
            '/api/casts',
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_cast()
    {
        $id = 2;

        $response = $this->get(
            "/api/casts/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_cast()
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
            '/api/casts',
            $data,
            $this->apiHeader()
        );
        
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_cast()
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
            "/api/casts/$id",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_casts()
    {
        $data = [
            'ids' => [2]
        ];

        $response = $this->delete(
            '/api/casts',
            $data
        );

        $this->assertResponse($response);
    }

}
