<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\Api\ApiServices;
use App\Traits\Api\ApiResponser;
use App\Traits\Auth\AuthServices;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Auth\LoginRequest;

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
        $withRoles = (bool) $request->input('withRoles', false);
        $withPermissions = (bool) $request->input('withPermissions', false);

        if (! Auth::attempt($request->validated())) {
            return $this->error('Login Failed! Your email or password is incorrect');
        }

        $auth = Auth::user();
        $auth->markedAsActive();

        $role = '';

        $data = [
            'user' => $auth->withoutRelations(),
            'profiles' => $auth->profiles
        ];

        if ($withRoles) 
        {
            $role = $auth->roles->first()?->withoutRelations()->name;

            $data = $data + [
                'role' => $role
            ];
        }

        if ($withPermissions && $role !== 'Subscriber') {
            $data = $data + [
                'permissions' => $this->authPermissionViaRoles($auth)
            ];
        }

        $data = ( $withPermissions && $role !== 'Subscriber' ) 
            ? $data + [ 'permissions' => $this->authPermissionViaRoles($auth) ]
            : $data + [ 'permissions' => [] ];

        return $this->token(
            $this->getPersonalAccessToken($request),
            'User logged in successfully.',
            $data
        );
    }


    /**
     * Sign out's the currently authenticated user
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = request()->user('api');

        $user->token()->revoke();
        $user->markedAsInActive();

        return $this->success([], 'User logged out successfully.');
    }
}
