<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'pseudonym' => 'Makoto Shinkai',
            'birth_name' => 'Makoto Niitsu',
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
            'pseudonym' => 'Makoto Shinkai',
            'birth_name' => 'Makoto Niitsu',
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
            "/api/directors/$id",
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
            "/api/directors/$id/enabled",
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    
    /** test */
    public function user_can_upload_directors_avatar()
    {
        $data = [
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 400, 600)
        ];

        $response = $this->post(
            "/api/directors/upload-avatar",
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
