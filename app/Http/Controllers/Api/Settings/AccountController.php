<?php

namespace App\Http\Controllers\Api\Settings;

use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Settings\Account\UpdateRequest;
use App\Http\Requests\Settings\Account\VerifyUserRequest;

class AccountController extends Controller
{
    use ApiResponser;

    
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Check if the user password is correct.
     *
     * @param VerifyUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(VerifyUserRequest $request)
    {
        $result = Hash::check($request->password, Auth::user()->password);

        return !$result
            ? $this->noContent()
            : $this->success();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    { 
        $request->user('api')
            ->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

        return $this->success(null, 'User account updated successfully.');
    }

}
