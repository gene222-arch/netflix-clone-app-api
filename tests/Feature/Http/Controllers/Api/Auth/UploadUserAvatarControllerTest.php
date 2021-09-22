<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadUserAvatarControllerTest extends TestCase
{

    /** test */
    public function user_can_upload_avatar()
    {
        $path = UploadedFile::fake()->image('avatar.jpg', 400, 400);

        $data = [
            'avatar' => $path
        ];

        $response = $this->post(
            '/api/auth/users/upload-avatar',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
