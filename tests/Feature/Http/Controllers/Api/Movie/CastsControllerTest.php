<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CastsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_casts()
    {
        $response = $this->get(
            '/api/casts',
            $this->apiHeader()
        );

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
            'birth_name' => 'Kotaro Daigo',
            'gender' => 'Male',
            'height_in_cm' => 167,
            'biographical_information' => 'Kotaro Daigo (醍醐虎汰朗, Daigo Kotaro) is a Japanese actor. He voiced Hodaka Morishima in Weathering With You.',
            'birth_details' => '',
            'date_of_birth' => '2000-09-01',
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
    public function user_can_update_enabled_status()
    {
        $id = 1;

        $response = $this->put(
            "/api/casts/$id/enabled",
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_upload_casts_avatar()
    {
        $data = [
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 400, 600)
        ];

        $response = $this->post(
            "/api/casts/upload-avatar",
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
