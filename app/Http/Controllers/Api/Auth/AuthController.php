<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckPasswordRequest;
use App\Traits\Auth\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use AuthServices;

    /**
     * Show the currently authenticated user
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $auth = $request->user();

        return $this->success([
            'user' => $auth,
            'permissions' => $this->authPermissionViaRoles(),
            'profiles' => $auth->profiles
        ]);
    }

    /**
     * Show the currently authenticated user
     * 
     * @param  App\Http\Requests\Auth\CheckPasswordRequest  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function checkPassword(CheckPasswordRequest $request)
    {
        $isPasswordMatched = Hash::check($request->password, $request->user()->password);

        return !$isPasswordMatched
            ? $this->error('Incorrect Password')
            : $this->success();
    }
}
