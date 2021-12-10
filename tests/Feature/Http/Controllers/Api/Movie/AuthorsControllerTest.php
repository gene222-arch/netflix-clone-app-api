<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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
            'avatar_path' => 'test',
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
        
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_author()
    {
        $id = 1;

        $data = [
            'avatar_path' => 'test',
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
    public function user_can_update_enabled_status()
    {
        $id = 1;

        $response = $this->put(
            "/api/authors/$id/enabled",
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_restore_soft_deleted_authors()
    {
        $data = [
            'ids' => [ 1 ]
        ];

        $response = $this->put(
            "/api/authors/restore",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_upload_authors_avatar()
    {
        $data = [
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 400, 600)
        ];

        $response = $this->post(
            "/api/authors/upload-avatar",
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
