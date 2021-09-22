<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Upload\UploadAvatarRequest;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Traits\Upload\HasUploadable;

class UploadUserAvatarController extends Controller
{
    use ApiResponser, HasUploadable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $poster = $this->upload(
            $request, 
            'poster', 
            'users/avatars/posters/', 
            320,
            320
        );

        return $this->success($poster);
    }
}
