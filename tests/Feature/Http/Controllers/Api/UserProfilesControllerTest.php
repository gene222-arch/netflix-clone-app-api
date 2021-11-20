<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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
            'name' => 'Philippians V',
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


    /** test */
    public function user_can_update_profile()
    {
        $id = 1;

        $data = [
            'id' => $id,
            'name' => 'Philippians the II',
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
    public function user_can_manage_user_profile_pin_code()
    {
        $id = 1;

        $data = [
            'user_profile_id' => $id,
            'pin_code' => '8425',
            'is_profile_locked' => true
        ];

        $response = $this->put(
            "/api/user-profiles/${id}/pin-code",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_disable_profile()
    {
        $data = [
            'ids' => [
                32
            ]
        ];

        $response = $this->put(
            "/api/user-profiles/disable",
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_upload_avatar()
    {
        $avatar = UploadedFile::fake()
            ->image('avatar.jpg', 400, 400)
            ->size(3);

        $data = [
            'avatar' => $avatar
        ];

        $response = $this->post(
            "/api/user-profiles/avatar-upload",
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
