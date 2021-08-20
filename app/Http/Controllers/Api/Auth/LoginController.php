<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\Api\ApiResponser;
use App\Traits\Api\ApiServices;
use App\Traits\Auth\AuthServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use ApiResponser, ApiServices, AuthServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest:api', 'verified'])->except('logout');
    }


    /**
     * Login's the user
     *
     * @param LoginRequest $request
     * @return json
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return $this->error('Credentials mismatch');
        }

        $auth = Auth::user();

        if (! $auth->hasVerifiedEmail()) {
            return $this->error('Your email address is not verified.');
        }

        return $this->token(
            $this->getPersonalAccessToken($request),
            'User logged in successfully.',
            [
                'user' => $auth->withoutRelations(),
                'profiles' => $auth->profiles
            ]
        );
    }


    /**
     * Sign out's the currently authenticated user
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        request()->user('api')->token()->revoke();

        return $this->success([], 'User logged out successfully.');
    }
}
