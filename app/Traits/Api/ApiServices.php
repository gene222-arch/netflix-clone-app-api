<?php

namespace App\Traits\Api;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;

trait ApiServices
{
    
   /**
     * Create a new personal access token
     * 
     * @return string
     */
    protected function getPersonalAccessToken($request)
    {
        if ($request->has('remember_me') && $request->remember_me)
        {
            Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(15));
        }

        return $this->guard()->user()->createToken(env('PERSONAL_ACCESS_TOKEN'));
    }


    /**
     * Guard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}