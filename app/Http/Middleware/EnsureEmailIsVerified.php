<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailIsVerified
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {   
        $user = $request->user('api');

        if (! $user) 
        {
            if (! $request->email) {
                return $this->error([
                    'email' => 'Email is required'
                ]);
            }

            $validator = Validator::make(
                $request->all(), 
                [ 'email' => ['required', 'email', 'exists:users'] ], 
                [ 'email.exists' => 'An account with this email address do not exists']
            );

            if ( $validator->fails() ) 
            {
                return $this->error([
                    'email' => $validator->errors()->first()
                ]);
            }

            $user = User::where('email', $request->email)->firstOrFail();
        }

        $isUserVerified = $user instanceof MustVerifyEmail && $user->hasVerifiedEmail();

        if (! $user || ! $isUserVerified) {
            return $request->expectsJson()
                    ? $this->error([
                        'email' => $request->email . ' is not verified'
                    ], 403)
                    : $this->success('Your email address is verified.');
        }

        return $next($request);
    }
}
