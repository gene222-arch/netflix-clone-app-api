<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Traits\Auth\AuthServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponser, AuthServices;


    public function __construct()
    {
        $this->middleware(['auth:api']);
    }


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
}
