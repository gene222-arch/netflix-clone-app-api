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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }
    
    /**
     * Upload Avatar
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $avatar = $this->upload(
            $request, 
            'avatar', 
            'users/avatars/', 
            320,
            320
        );

        return $this->success($avatar);
    }
}
