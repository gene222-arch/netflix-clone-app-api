<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateEmailRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Notifications\ChangeEmailVerificationNotification;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Users']);
    }


    /**
     * Display a listing of the user's users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::withCount('roles')
            ->whereHas('roles', fn($q) => $q->where('name', '!=', 'Subscriber'))
            ->having('roles_count', 0)
            ->get()
            ->except(1);

        return !$users->count()
            ? $this->noContent()
            : $this->success($users, 'Users fetched successfully.');
    }

    /**
     * Get specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserByToken()
    {
        $auth = request()->user('api');

        return $this->success([
            'user' => $auth->withoutRelations(),
            'profiles' => $auth->profiles,
            'role' => $auth->roles->first()?->withoutRelations()->name
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\User\UpdateEmailRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmail(UpdateEmailRequest $request)
    {
        $request->user('api')->update([
            'email' => $request->email
        ]);

        return $this->success(null, 'Account email updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\User\UpdatePasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user('api')->update([
            'password' => Hash::make($request->password)
        ]);

        return $this->success(null, 'Account password updated successfully.');
    }

    /**
     * Send an email message.
     *
     * @param  App\Http\Requests\User\UpdatePasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmailVerificationCode()
    {
        $code = \random_int(100000, 999999);
        
        request()
            ->user('api')
            ->notify(new ChangeEmailVerificationNotification($code));

        return $this->success($code, 'An Email Verification is being sent to you within a few seconds.');
    }
}
