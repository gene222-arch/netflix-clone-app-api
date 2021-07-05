<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfilesControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_profiles()
    {
        $response = $this->get(
            '/api/user-profiles',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_profile()
    {
        $id = 1;

        $response = $this->get(
            "/api/user-profiles/${id}",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_profile()
    {
        $data = [
            'name' => 'Philippians',
            'avatar' => 'https://mir-s3-cdn-cf.behance.net/project_modules/disp/84c20033850498.56ba69ac290ea.png',
            'is_for_kids' => false
        ];

        $response = $this->post(
            '/api/user-profiles',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** @test */
    public function user_can_update_profile()
    {
        $id = 1;

        $data = [
            'name' => 'Philippians II',
            'avatar' => 'https://mir-s3-cdn-cf.behance.net/project_modules/disp/84c20033850498.56ba69ac290ea.png',
            'is_for_kids' => false
        ];

        $response = $this->put(
            "/api/user-profiles/${id}",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_profile()
    {
        $id = 1;

        $response = $this->delete(
            "/api/user-profiles/${id}",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
